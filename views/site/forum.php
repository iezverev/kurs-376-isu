<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


$this->title = 'Форум';
?>


<table class="table table-hover">
        <thead>
            <tr>
                <th>Номер темы</th>
                <th>Название</th>
                <th>Описание</th>
                <th>Время создания</th>
                <th>Автор</th>
                <th>Ссылка</th>
            </tr>
        </thead>
        <tbody>
<?php 
foreach ($themes as $theme) {

    
echo '<tr class="success"><td>'.$theme->id.'</td>
                <td>'.$theme->name.'</td>
                <td>'.$theme->description.'</td>
                <td>'.$theme->datetime.'</td>
                <td>'.$theme->user->username.'</td>
                <td>'; ?><a href="themepage?id=<?= $theme->id; ?>">

                    <button type="button" class="btn btn-info btn-sm">Дискасс</button></a>
                    <?php 

                    if (Yii::$app->user->identity->role == 'admin') {
                        echo '<a href="forum?idredact='.$theme->id.'"><button type="button" class="btn btn-info btn-sm" style="margin-left: 10px;">Редактировать</button></a><a href="removetheme?id='.$theme->id.'"><button type="button" class="btn btn-danger btn-sm" style="margin-left: 10px;">Удалить</button></a>'; 
                        }
                         echo '</td>
                            </tr>';
                    }


?>


</tbody>
</table>
<?php
echo '<div class="row">
            <div class="col-lg-5">'; ?>

               <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'name')->textInput()->label('Название')  ?>
                    <?= $form->field($model, 'description')->textInput()->label('Описание') ?>
                    <?= $form->field($model, 'datetime')->input('datetime-local')->label('Время создания')  ?>
                    <?= $form->field($model, 'id_user')->textInput(['style' => 'display:none', 'value' => Yii::$app->user->identity->id])->label('') ?>
                     

                    <?php echo '<div class="form-group">'; ?>
                         <?= Html::submitButton($buttonname.' тему', ['class' => 'btn btn-primary']) ?>
                         <?php if ($buttonname == 'Редактировать') {
                            echo '<a href="forum"><button type="button" class="btn btn-danger">Отмена</button></a>';
                         }?>
                    <?php echo '</div>'; ?>

                <?php ActiveForm::end(); ?>

            <?php echo '</div>'; ?>
        <?php echo '</div>'; ?>