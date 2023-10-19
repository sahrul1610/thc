<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php
    $form = ActiveForm::begin([
                'options' => [
                    'class' => 'needs-validation was-validated'
                ]
    ]);
    ?>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="user-person_id">NIK</label>
                <select class="form-control" id="user-person_id" name="User[person_id]">
                    <option value="<?php echo $model->person_id; ?>" selected="selected"><?php echo $model->employee->employee_id; ?> / <?php echo $model->employee->person_name; ?></option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'required' => true]) ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <?= $form->field($model, 'is_active')->checkbox() ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">		
                <?= $form->field($model, 'is_ldap')->checkbox() ?>
            </div>
        </div>
    </div>

    <div class="form-group text-right">
        <?= Html::submitButton('<i class="icofont icofont-save"></i> Submit', ['class' => 'btn btn-sm btn-pill btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    $(document).ready(function () {
        $("#user-person_id").select2({
            placeholder: "Cari Karyawan",
            ajax: {
                url: '<?php echo Url::toRoute([Yii::$app->controller->id . '/showlistkaryawan']); ?>',
                type: 'GET',
                dataType: 'json',
                data: function (params) {
                    return {
                        key: params.term,
                        page: params.page
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.items,
                        pagination: {
                            more: (params.page * 10) < data.total_count
                        }
                    };
                },
            }
        });
    });
</script>
