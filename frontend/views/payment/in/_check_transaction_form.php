<?php

/**
 * @var View $this
 */

use yii\helpers\Url;
use yii\web\View; ?>
<h3>Авторизовать транзакцию</h3>
<form action="<?= Url::toRoute('/payment/in/check-transaction') ?>" method="get">
    <div class="form-group">
        <input type="text" name="tx" class="form-control" placeholder="Ключ транзакции">
        <button type="submit" class="btn btn-success">
            Авторизовать
        </button>
    </div>
</form>
