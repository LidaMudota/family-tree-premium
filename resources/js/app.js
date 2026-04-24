import './bootstrap';

const d = window.familyTreeData;
if (d) {
    const svg = document.getElementById('treeSvg');
    const NS = 'http://www.w3.org/2000/svg';
    const g = document.createElementNS(NS, 'g');
    svg.appendChild(g);

    let viewport = d.viewport || {x: 20, y: 20, scale: 1};
    const nodes = d.people.map((p, i) => ({...p, x: (i % 6) * 220 + 20, y: Math.floor(i / 6) * 140 + 20}));

    const map = Object.fromEntries(nodes.map(n => [n.id, n]));

    d.links.forEach(link => {
        const s = map[link.source]; const t = map[link.target];
        if (!s || !t) return;
        const line = document.createElementNS(NS, 'line');
        line.setAttribute('class', 'edge');
        line.setAttribute('x1', s.x + 90); line.setAttribute('y1', s.y + 40);
        line.setAttribute('x2', t.x + 90); line.setAttribute('y2', t.y + 40);
        g.appendChild(line);
    });

    nodes.forEach(node => {
        const box = document.createElementNS(NS, 'g');
        box.dataset.id = node.id;
        const rect = document.createElementNS(NS, 'rect');
        rect.setAttribute('x', node.x); rect.setAttribute('y', node.y); rect.setAttribute('width', 180); rect.setAttribute('height', 80); rect.setAttribute('class', 'node-rect');
        const text = document.createElementNS(NS, 'text');
        text.setAttribute('x', node.x + 10); text.setAttribute('y', node.y + 30); text.setAttribute('class', 'node-text'); text.textContent = node.name;
        const note = document.createElementNS(NS, 'text');
        note.setAttribute('x', node.x + 10); note.setAttribute('y', node.y + 52); note.setAttribute('class', 'node-text'); note.textContent = (node.summary || '').slice(0, 24);
        box.append(rect, text, note);
        g.appendChild(box);
    });

    const updateTransform = () => g.setAttribute('transform', `translate(${viewport.x}, ${viewport.y}) scale(${viewport.scale})`);
    updateTransform();

    let panning = false, sx=0, sy=0;
    svg.addEventListener('mousedown', e => { panning = true; sx = e.clientX; sy = e.clientY;});
    window.addEventListener('mouseup', () => panning = false);
    window.addEventListener('mousemove', e => {
        if (!panning) return;
        viewport.x += e.clientX - sx; viewport.y += e.clientY - sy; sx = e.clientX; sy = e.clientY; updateTransform();
    });
    svg.addEventListener('wheel', e => { e.preventDefault(); viewport.scale = Math.max(0.2, Math.min(2.5, viewport.scale + (e.deltaY < 0 ? 0.1 : -0.1))); updateTransform(); }, {passive:false});

    document.getElementById('fitBtn')?.addEventListener('click', () => { viewport = {x: 30, y: 30, scale: 1}; updateTransform(); });
    document.getElementById('centerBtn')?.addEventListener('click', () => { viewport.x = 100; viewport.y = 60; updateTransform(); });

    document.getElementById('searchPerson')?.addEventListener('input', async (e) => {
        const q = e.target.value.trim();
        if (q.length < 2) return;
        const r = await fetch(`/trees/${d.treeId}/search?q=${encodeURIComponent(q)}`);
        const list = await r.json();
        const first = list[0];
        if (!first) return;
        const node = map[first.id];
        if (!node) return;
        viewport.x = 300 - node.x; viewport.y = 220 - node.y; updateTransform();
    });

    document.getElementById('exportPngBtn')?.addEventListener('click', async () => {
        const xml = new XMLSerializer().serializeToString(svg);
        const img = new Image();
        img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(xml)));
        await new Promise(resolve => img.onload = resolve);
        const canvas = document.createElement('canvas');
        canvas.width = svg.clientWidth * 2; canvas.height = svg.clientHeight * 2;
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = '#fff'; ctx.fillRect(0, 0, canvas.width, canvas.height); ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        const data = canvas.toDataURL('image/png');
        const form = new FormData(); form.append('_token', d.csrf); form.append('image', data);
        const resp = await fetch(`/trees/${d.treeId}/export/png`, {method: 'POST', body: form});
        const blob = await resp.blob();
        const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = `tree-${d.treeId}.png`; a.click();
    });

    setInterval(() => {
        fetch(`/trees/${d.treeId}/viewport`, {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':d.csrf},
            body: JSON.stringify(viewport),
        });
    }, 8000);
}

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
