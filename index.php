<?php
// Путь к файлу с данными
$filename = 'notes.json';

// 1. Чтение данных из файла
if (file_exists($filename)) {
    $jsonData = file_get_contents($filename);
    $notes = json_decode($jsonData, true); // true — получить как ассоциативный массив
} else {
    $notes = []; // если файла нет — пустой массив
}

// 2. Проверка, что данные — массив (на случай пустого файла)
if (!is_array($notes)) {
    $notes = [];
}

// 3. Обработка формы добавления заметки
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && isset($_POST['text'])) {
    $title = trim($_POST['title']);
    $text = trim($_POST['text']);

    if ($title !== '' && $text !== '') {
        // Создаём новую заметку
        $newNote = [
            'id' => time(), // уникальный идентификатор на основе времени
            'title' => $title,
            'text' => $text
        ];

        // Добавляем в массив
        $notes[] = $newNote;

        // Сохраняем обратно в JSON-файл
        file_put_contents($filename, json_encode($notes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    // Перенаправление, чтобы избежать повторной отправки формы при обновлении страницы
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Каталог заметок</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Каталог заметок</h1>

    <!-- Форма добавления заметки -->
    <h2>Добавить новую заметку</h2>
    <form method="POST">
        <label for="title">Заголовок:</label>
        <input type="text" name="title" id="title" required>

        <label for="text">Текст заметки:</label>
        <textarea name="text" id="text" rows="4" required></textarea>

        <button type="submit">Сохранить</button>
    </form>

    <!-- Вывод списка заметок -->
    <h2>Список заметок</h2>

    <?php if (empty($notes)): ?>
        <p>Заметок пока нет. Добавьте первую!</p>
    <?php else: ?>
        <?php foreach ($notes as $note): ?>
            <div class="note">
                <h3><?= htmlspecialchars($note['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($note['text'])) ?></p>
                <small>ID: <?= $note['id'] ?></small>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>