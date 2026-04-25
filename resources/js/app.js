import './bootstrap';

const safeJsonParse = (value, fallback) => {
    if (typeof value !== 'string' || value.trim() === '') return fallback;

    try {
        const parsed = JSON.parse(value);
        return parsed ?? fallback;
    } catch (error) {
        console.error('Не удалось разобрать JSON из data-атрибута:', error);
        return fallback;
    }
};

const normalizePeople = (value) => {
    if (!Array.isArray(value)) return [];

    return value
        .filter((person) => person && typeof person === 'object')
        .map((person) => ({
            id: Number.isFinite(Number(person.id)) ? Number(person.id) : null,
            name: String(person.name ?? '').trim(),
            summary: String(person.summary ?? '').trim(),
            photo: typeof person.photo === 'string' ? person.photo : null,
            birthDate: typeof person.birthDate === 'string' ? person.birthDate : null,
        }));
};

const normalizeLinks = (value) => {
    if (!Array.isArray(value)) return [];

    return value
        .filter((link) => link && typeof link === 'object')
        .map((link) => ({
            source: Number.isFinite(Number(link.source)) ? Number(link.source) : null,
            target: Number.isFinite(Number(link.target)) ? Number(link.target) : null,
            type: String(link.type ?? '').trim(),
        }))
        .filter((link) => link.source && link.target);
};

const initTreeBuilder = () => {
    const root = document.getElementById('familyTreeData');
    const treeRoot = document.getElementById('treeBuilder');
    const list = document.getElementById('peopleCards');
    const modal = document.getElementById('personModal');
    const form = document.getElementById('personCreateForm');

    if (!root || !treeRoot || !list || !modal || !form) return;

    const treeId = Number(root.dataset.treeId);
    const csrf = root.dataset.csrf || '';
    const initialPeople = normalizePeople(safeJsonParse(root.dataset.people, []));
    const links = normalizeLinks(safeJsonParse(root.dataset.links, []));

    if (!Number.isInteger(treeId) || treeId <= 0) {
        console.error('Некорректный treeId для конструктора.', { treeId });
        treeRoot.dataset.state = 'error';
        const stateBlock = document.getElementById('treeState');
        if (stateBlock) {
            stateBlock.textContent = 'Не удалось загрузить дерево. Обновите страницу.';
        }
        return;
    }

    let people = initialPeople;

    const alertBox = document.getElementById('personFormAlert');
    const fieldErrors = {
        first_name: document.getElementById('error_first_name'),
        last_name: document.getElementById('error_last_name'),
        birth_date: document.getElementById('error_birth_date'),
        summary_note: document.getElementById('error_summary_note'),
        photo: document.getElementById('error_photo'),
    };

    const toggleModal = (open) => {
        modal.classList.toggle('is-open', open);
        modal.setAttribute('aria-hidden', open ? 'false' : 'true');

        if (open) {
            document.body.classList.add('modal-open');
            form.querySelector('input[name="first_name"]')?.focus();
        } else {
            document.body.classList.remove('modal-open');
        }
    };

    const showAlert = (message, type = 'danger') => {
        if (!alertBox) return;
        alertBox.className = `alert ${type}`;
        alertBox.textContent = message;
        alertBox.hidden = false;
    };

    const clearAlert = () => {
        if (!alertBox) return;
        alertBox.hidden = true;
        alertBox.textContent = '';
    };

    const clearFieldErrors = () => {
        Object.values(fieldErrors).forEach((node) => {
            if (!node) return;
            node.textContent = '';
            node.hidden = true;
        });

        form.querySelectorAll('.input-error').forEach((input) => input.classList.remove('input-error'));
    };

    const draw = () => {
        list.innerHTML = '';

        if (!people.length) {
            const empty = document.createElement('p');
            empty.className = 'tree-empty-text';
            empty.textContent = 'Пока нет персон. Нажмите на карточку с плюсом, чтобы добавить первую.';
            list.append(empty);
        }

        people.forEach((person) => {
            const card = document.createElement('article');
            card.className = 'person-card';
            card.innerHTML = `
                <div class="person-avatar">
                    ${person.photo ? `<img src="${person.photo}" alt="${person.name || 'Персона'}">` : '<span aria-hidden="true">���</span>'}
                </div>
                <h3>${person.name || 'Без имени'}</h3>
                <p>${person.birthDate ? `Рождение: ${person.birthDate}` : 'Дата рождения не указана'}</p>
                <p>${person.summary || 'Описание пока не добавлено.'}</p>
            `;
            list.append(card);
        });

        const addCard = document.createElement('button');
        addCard.type = 'button';
        addCard.className = 'person-card add-person-card';
        addCard.innerHTML = '<span class="plus-icon" aria-hidden="true">＋</span><span>Добавить персону</span>';
        addCard.addEventListener('click', () => {
            clearAlert();
            clearFieldErrors();
            toggleModal(true);
        });
        list.append(addCard);

        const stateBlock = document.getElementById('treeState');
        if (stateBlock) {
            stateBlock.textContent = links.length
                ? `Персон: ${people.length}. Связей: ${links.length}.`
                : `Персон: ${people.length}. Добавьте связи после заполнения карточек.`;
        }
    };

    draw();

    document.querySelectorAll('[data-open-person-modal]').forEach((button) => {
        button.addEventListener('click', () => {
            clearAlert();
            clearFieldErrors();
            toggleModal(true);
        });
    });

    document.querySelectorAll('[data-close-person-modal]').forEach((button) => {
        button.addEventListener('click', () => toggleModal(false));
    });

    modal.addEventListener('click', (event) => {
        if (event.target === modal) toggleModal(false);
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearAlert();
        clearFieldErrors();

        const submitButton = form.querySelector('button[type="submit"]');
        submitButton?.setAttribute('disabled', 'disabled');

        const formData = new FormData(form);

        formData.set('gender', 'unknown');
        formData.set('life_status', 'unknown');
        formData.set('birth_date_precision', formData.get('birth_date') ? 'full' : 'unknown');
        formData.set('death_date_precision', 'unknown');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    Accept: 'application/json',
                },
                body: formData,
            });

            const payload = await response.json().catch(() => ({}));

            if (!response.ok) {
                if (response.status === 422 && payload.errors) {
                    Object.entries(payload.errors).forEach(([field, messages]) => {
                        const fieldError = fieldErrors[field];
                        if (!fieldError) return;
                        fieldError.textContent = Array.isArray(messages) ? messages[0] : String(messages);
                        fieldError.hidden = false;
                        form.querySelector(`[name="${field}"]`)?.classList.add('input-error');
                    });

                    showAlert('Проверьте поля формы и исправьте ошибки.', 'danger');
                    return;
                }

                console.error('Ошибка сохранения персоны:', payload);
                showAlert(payload.message || 'Не удалось сохранить персону. Попробуйте ещё раз.', 'danger');
                return;
            }

            if (!payload.person) {
                showAlert('Персона сохранена, но данные ответа неполные. Обновите страницу.', 'success');
                window.location.reload();
                return;
            }

            people = [...people, payload.person];
            draw();
            form.reset();
            showAlert('Персона успешно добавлена.', 'success');
            toggleModal(false);
        } catch (error) {
            console.error('Сетевая ошибка при добавлении персоны:', error);
            showAlert('Сервер временно недоступен. Проверьте соединение и повторите попытку.', 'danger');
        } finally {
            submitButton?.removeAttribute('disabled');
        }
    });
};

initTreeBuilder();

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const revealElements = document.querySelectorAll('[data-reveal]');

if (revealElements.length) {
    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
        revealElements.forEach((el) => el.classList.add('is-visible'));
    } else {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -30px 0px' });

        revealElements.forEach((el, index) => {
            el.style.transitionDelay = `${Math.min(index * 65, 320)}ms`;
            observer.observe(el);
        });
    }
}
