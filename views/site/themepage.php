







<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
 

$this->title = 'Информация об объявлении';
?>
<h1> Название:
    <?php  
    echo $themeinfo->name;
    ?>
</h1>
<h2> Описание:
    <?php  
    echo $themeinfo->description;
    ?>
</h2>
<div style="width: 1200px;">
    <ul class="list-group">
        <?php
            foreach ($messages as $mess) {
                echo '<li class="list-group-item"><h4>'.$mess->user->username.'</h4><br>
                      <p>'.$mess->text.'</p>';
                      if ($mess->imageFile != 'none') {
                                echo '<img src="../web/'.$mess->imageFile.'" width="700px" height="500px">';
                            }      
                      echo '</li>';   
            }
        ?>
    </ul>
</div>
<div>
<?php

  
    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

        <?= $form->field($model, 'text')->textInput()->label('Текст') ?>
        <?= $form->field($model, 'imageFile')->fileInput() ?>
        <?= Html::submitButton('Оставить сообщение', ['class' => 'btn btn-primary']) ?>
        <?= $form->field($model, 'id_theme')->textInput(['style' => 'display:none', 'value' => $id])->label('') ?>
        <?= $form->field($model, 'id_user')->textInput(['style' => 'display:none', 'value' => Yii::$app->user->identity->id])->label('') ?>


<?php ActiveForm::end() ?>

</div>
