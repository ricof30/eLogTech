<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Prediction Results</title>
</head>
<body>
    <h1>Prediction Results</h1>
    <?php if (isset($result) && !empty($result)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Sample</th>
                    <th>Prediction</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($result as $index => $prediction): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($prediction) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No results to show.</p>
    <?php endif; ?>
</body>
</html>
