<label>Имя<input name="first_name" required></label>
<label>Фамилия<input name="last_name"></label>
<label>Отчество<input name="middle_name"></label>
<label>Фамилия при рождении<input name="birth_last_name"></label>
<label>Пол<select name="gender"><option value="unknown">Неизвестно</option><option value="male">Мужчина</option><option value="female">Женщина</option></select></label>
<label>Статус<select name="life_status"><option value="unknown">Неизвестно</option><option value="alive">Жив</option><option value="deceased">Умер</option></select></label>
<label>Точность рождения<select name="birth_date_precision"><option value="unknown">Неизвестно</option><option value="year">Год</option><option value="month_year">Месяц+год</option><option value="full">Полная дата</option></select></label>
<label>Дата рождения<input type="date" name="birth_date"></label>
<label>Год рождения<input type="number" name="birth_year"></label>
<label>Месяц рождения<input type="number" name="birth_month" min="1" max="12"></label>
<label>Место рождения<input name="birth_place"></label>
<label>Точность смерти<select name="death_date_precision"><option value="unknown">Неизвестно</option><option value="year">Год</option><option value="month_year">Месяц+год</option><option value="full">Полная дата</option></select></label>
<label>Дата смерти<input type="date" name="death_date"></label>
<label>Год смерти<input type="number" name="death_year"></label>
<label>Месяц смерти<input type="number" name="death_month" min="1" max="12"></label>
<label>Место смерти<input name="death_place"></label>
<label>Короткая заметка<input name="summary_note"></label>
<label>Полная заметка<textarea name="full_note"></textarea></label>
<label>Фото<input type="file" name="photo" accept="image/png,image/jpeg,image/webp"></label>
