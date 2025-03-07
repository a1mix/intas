<?php

use App\Database\Connection;

$pdo = Connection::get()->connect();

$couriers = $pdo->query("SELECT * FROM couriers")->fetchAll(PDO::FETCH_ASSOC);
$regions = $pdo->query("SELECT * FROM regions")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить поездку</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1 class="text-center mb-4">Добавить поездку</h1>
    <form id="addTripForm" class="bg-white p-4 rounded shadow">
        <div class="mb-3">
            <label for="region_id" class="form-label">Регион:</label>
            <select name="region_id" id="region_id" class="form-select">
                <?php foreach ($regions as $region): ?>
                    <option value="<?= $region['id'] ?>"><?= htmlspecialchars($region['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="courier_id" class="form-label">Курьер:</label>
            <select name="courier_id" id="courier_id" class="form-select">
                <?php foreach ($couriers as $courier): ?>
                    <option value="<?= $courier['id'] ?>"><?= htmlspecialchars($courier['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="departure_date" class="form-label">Дата выезда:</label>
            <input type="date" name="departure_date" id="departure_date" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary w-100" id="submitButton">
            <span id="buttonText">Добавить</span>
            <span id="buttonSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
        </button>
    </form>

    <div id="responseMessage" class="mt-3"></div>
</div>

<script>
    $(document).ready(function() {
        $('#addTripForm').on('submit', function(e) {
            e.preventDefault();

            const buttonText = $('#buttonText');
            const buttonSpinner = $('#buttonSpinner');
            const submitButton = $('#submitButton');

            submitButton.prop('disabled', true);
            buttonText.hide();
            buttonSpinner.show();

            $.ajax({
                url: '/api/schedule',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#responseMessage').html(
                        '<div class="alert alert-success">' + response.message + '</div>'
                    );
                },
                error: function(xhr) {
                    const errorMessage = xhr.responseJSON ? xhr.responseJSON.message : 'Произошла ошибка';
                    $('#responseMessage').html(
                        '<div class="alert alert-danger">' + errorMessage + '</div>'
                    );
                },
                complete: function() {
                    submitButton.prop('disabled', false);
                    buttonText.show();
                    buttonSpinner.hide();
                }
            });
        });
    });
</script>
</body>
</html>