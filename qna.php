<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/../includes/db.php';

// Add new Q&A
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $stmt = $pdo->prepare("INSERT INTO qna (category_id, question, keywords, answer) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['category_id'], $_POST['question'], $_POST['keywords'], $_POST['answer']]);
        $message = "✅ Question added!";
    } elseif ($_POST['action'] === 'edit') {
        $stmt = $pdo->prepare("UPDATE qna SET category_id=?, question=?, keywords=?, answer=?, status=? WHERE id=?");
        $stmt->execute([$_POST['category_id'], $_POST['question'], $_POST['keywords'], $_POST['answer'], $_POST['status'], $_POST['id']]);
        $message = "✅ Question updated!";
    } elseif ($_POST['action'] === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM qna WHERE id=?");
        $stmt->execute([$_POST['id']]);
        $message = "🗑️ Question deleted!";
    }
}

// Fetch all Q&A
$qna = $pdo->query("SELECT q.*, c.name as category_name FROM qna q LEFT JOIN categories c ON q.category_id = c.id ORDER BY q.id DESC")->fetchAll(PDO::FETCH_ASSOC);
$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Q&A - Fire Safety Bot</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial; display: flex; min-height: 100vh; }
        .sidebar { width: 220px; background: #2c3e50; color: white; padding: 20px; }
        .sidebar h3 { margin-bottom: 20px; }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 10px 0; }
        .main { flex: 1; padding: 30px; background: #ecf0f1; }
        .card { background: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { background: #075e54; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border-bottom: 1px solid #eee; }
        input, select, textarea { width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 5px; font-family: inherit; }
        button { padding: 10px 20px; background: #075e54; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .btn-danger { background: #e74c3c; }
        .btn-edit { background: #f39c12; }
        .message { padding: 10px; background: #d4edda; color: #155724; border-radius: 5px; margin-bottom: 15px; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; }
        .modal.show { display: flex; }
        .modal-box { background: white; padding: 30px; border-radius: 10px; width: 90%; max-width: 600px; max-height: 80vh; overflow-y: auto; }
        .badge { padding: 3px 8px; border-radius: 10px; font-size: 11px; }
        .badge-active { background: #d4edda; color: #155724; }
        .badge-inactive { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>🔥 Fire Safety Bot</h3>
        <a href="dashboard.php">📊 Dashboard</a>
        <a href="qna.php">📝 Manage Q&A</a>
        <a href="logout.php">🚪 Logout</a>
    </div>
    <div class="main">
        <h2>📝 Manage Questions & Answers</h2>
        
        <?php if (isset($message)) echo "<div class='message'>$message</div>"; ?>
        
        <!-- Add Form -->
        <div class="card">
            <h3>Add New Question</h3>
            <form method="post">
                <input type="hidden" name="action" value="add">
                <select name="category_id" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="question" placeholder="Question" required>
                <input type="text" name="keywords" placeholder="Keywords (comma separated)">
                <textarea name="answer" rows="4" placeholder="Answer" required></textarea>
                <button type="submit">Add Question</button>
            </form>
        </div>
        
        <!-- Q&A List -->
        <div class="card">
            <h3>All Questions (<?= count($qna) ?>)</h3>
            <table>
                <tr><th>ID</th><th>Category</th><th>Question</th><th>Keywords</th><th>Status</th><th>Actions</th></tr>
                <?php foreach ($qna as $q): ?>
                <tr>
                    <td><?= $q['id'] ?></td>
                    <td><?= htmlspecialchars($q['category_name'] ?? '-') ?></td>
                    <td><?= htmlspecialchars(substr($q['question'], 0, 50)) ?>...</td>
                    <td><?= htmlspecialchars(substr($q['keywords'], 0, 30)) ?>...</td>
                    <td><span class="badge badge-<?= $q['status'] ?>"><?= $q['status'] ?></span></td>
                    <td>
                        <button class="btn-edit" onclick="editQna(<?= $q['id'] ?>, '<?= htmlspecialchars(addslashes($q['question'])) ?>', '<?= htmlspecialchars(addslashes($q['keywords'])) ?>', '<?= htmlspecialchars(addslashes($q['answer'])) ?>', <?= $q['category_id'] ?>, '<?= $q['status'] ?>')">✏️</button>
                        <form method="post" style="display:inline" onsubmit="return confirm('Delete this question?')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $q['id'] ?>">
                            <button class="btn-danger">🗑️</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    
    <!-- Edit Modal -->
    <div class="modal" id="editModal">
        <div class="modal-box">
            <h3>Edit Question</h3>
            <form method="post">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="edit_id">
                <select name="category_id" id="edit_category" required>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="question" id="edit_question" placeholder="Question" required>
                <input type="text" name="keywords" id="edit_keywords" placeholder="Keywords">
                <textarea name="answer" id="edit_answer" rows="4" placeholder="Answer" required></textarea>
                <select name="status" id="edit_status">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <button type="submit">Update</button>
                <button type="button" class="btn-danger" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>
    
    <script>
        function editQna(id, question, keywords, answer, category_id, status) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_question').value = question;
            document.getElementById('edit_keywords').value = keywords;
            document.getElementById('edit_answer').value = answer;
            document.getElementById('edit_category').value = category_id;
            document.getElementById('edit_status').value = status;
            document.getElementById('editModal').classList.add('show');
        }
        function closeModal() {
            document.getElementById('editModal').classList.remove('show');
        }
    </script>
</body>
</html>