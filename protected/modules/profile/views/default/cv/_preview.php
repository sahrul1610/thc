<?php
	use Yii;
	use app\components\Logic;
?>

<div class="width-100">
	<div class="float-start width-20">
		<div style="padding-top:5px;">
			<img width="130" src="<?=Logic::getFile($model->url_photo); ?>">
		</div>
	</div>
	<div class="float-end width-80">
		<div style="font-weight:900;"><h2><?= $model->person_name.'  '.$model->employee_id; ?></h2></div>
		<div class="border1-solid-black"></div>
		<div class="width-100">
			<div class="float-start width-100">
				<p><?= (empty($model->org_name)) ? '-' : $model->org_name; ?></p>
				<p><?= (empty($model->org_unit_code)) ? '-' : $model->org_unit_name; ?></p>
				<p><?= ($model->band_name != '') ? $model->band_name : '-'; ?> / <?= ($model->psa_name != '') ? $model->psa_name : '-' ?></p>
			</div>
		</div>
	</div>
</div>
<br>
<div>
	<h2>DATA PRIBADI</h2>
	<table>
        <tr>
            <td>
                <ul><li>Tempat, Tanggal Lahir</li></ul>
            </td>
            <td>:</td>
            <td>
				<?= $model->town_of_birth_id != '' ? $model->town_of_birth_name : '-'; ?>, <?= Logic::getIndoDate($model->date_of_birth) == '' ? '-' : Logic::getIndoDate($model->date_of_birth); ?>
            </td>
        </tr>
        <tr>
            <td>    
				<ul><li>Alamat</li></ul>
            </td>
            <td>:</td>
            <td>
                <?= $alamat->address;?>, <?= $alamat->location_name; ?>
            </td>
        </tr>
        <tr>
            <td>
                <ul><li>Nomor Telephone</li></ul>
            </td>
            <td>:</td>
            <td>
				<?= $no_contact->no_contact; ?>
            </td>
        </tr>
        <tr>
            <td>
                <ul><li>Suku</li></ul>
            </td>
            <td>:</td>
            <td>
				<?= $model->ethnic_id != '' ? $model->ethnic_name : '-'; ?>
            </td>
        </tr>
        <tr>
            <td>
                <ul><li>Jenis Kelamin</li></ul>
            </td>
            <td>:</td>
            <td>
				<?= $model->sex == 'L' ? 'Pria' : 'Wanita'; ?>
            </td>
        </tr>
        <tr>
            <td>
                <ul><li>Agama</li></ul>
            </td>
            <td>:</td>
            <td>
			<?= $model->religion_name; ?>
            </td>
        </tr>
        <tr>
            <td>
                <ul><li>No Identitas</li></ul>
            </td>
            <td>:</td>
            <td>
                <?= $identitas->no_identity; ?>
            </td>
        </tr>
        <tr>
            <td>
                <ul><li>Status</li></ul>
            </td>
            <td>:</td>
            <td>
				<?= $model->marital_id != '' ? $model->marital_name : '-'; ?>
            </td>
        </tr>
    </table>
    <br>
	<div class="border1-solid-black"></div>
	<br>
    <?php
    if(!empty($pendidikan)){   
    ?>
	<h2>PENDIDIKAN</h2>
	<table>
        <?php 
            if(!empty($pendidikan)){ 
			foreach($pendidikan as $mdx=>$pendidikan){
		?>
        <tr>
            <td>
                <ul><li><?= $pendidikan->institute; ?></li></ul>
            </td>
            <td>:</td>
            <td>
				(<?= $pendidikan->year_of_study; ?>-<?= $pendidikan->year_of_passed;?>)
            </td>
        </tr>
        <?php 
            }
        } 
        ?>
    </table>
    <br>
	<div class="border1-solid-black"></div>
	<br>
    <?php
    }else{
    ?>
    
    <?php
    }
    ?>
    <?php
    if(!empty($pelatihan)){   
    ?>
	<h2>PELATIHAN</h2>
	<table>
		<?php 
            if(!empty($pelatihan)){ 
			foreach($pelatihan as $mdx=>$pelatihan){
			//var_dump($pelatihan);exit;
		?>
		<tbody>
		<tr>
            <td>
                Nama Pelatihan
            </td>
            <td>:</td>
            <td>
				<?= $pelatihan->title; ?>
            </td>
        </tr>
        <tr>
            <td>
                Lokasi Pelatihan    
            </td>
            <td>:</td>
            <td>
				<?= $pelatihan->location; ?>
            </td>
        </tr>
        <tr>
            <td>
                Jenis Pelatihan 
            </td>
            <td>:</td>
            <td>
				<?= $pelatihan->trg_name; ?>
            </td>
        </tr>
        <tr>
            <td>
                Tanggal Mulai Pelatihan 
            </td>
            <td>:</td>
            <td>
				<?= $pelatihan->start_of_training; ?>
            </td>
        </tr>
        <tr>
            <td>
                Tanggal Selesai Pelatihan   
            </td>
            <td>:</td>
            <td>
                <?= $pelatihan->start_of_training; ?>
            </td>
        </tr>
		<tr>
            <td>
                <br>
            </td>
        </tr>
		</tbody>
		<?php 
            }
        } 
        ?>
    </table>
	<div class="border1-solid-black"></div>
	<br>
    <?php
    }else{
    ?>
    
    <?php
    }
    ?>

    <?php
    if(!empty($kontak)){   
    ?>
	<h2>KONTAK</h2>
	<table>
		<tbody>
        <?php 
            if(!empty($kontak)){ 
			foreach($kontak as $mdx=>$kontak){
		?>
		<tr>
            <td>
                <li><?= $kontak->no_contact; ?></li>
            </td>
        </tr>
        <?php
            }
        }
        ?>
		</tbody>
        <br>
    </table>
	<div class="border1-solid-black"></div>
	<br>
    <?php
    }else{
    ?>
    
    <?php
    }
    ?>

    <?php
    if(!empty($keluarga)){   
    ?>
	<h2>KELUARGA</h2>
	<table>
		<tbody>
        <?php 
            if(!empty($keluarga)){ 
			foreach($keluarga as $mdx=>$keluarga){
		?>
		<tr>
            <td>
                <li><?= $keluarga->famtype_name; ?></li>
            </td>
            <td>:</td>
            <td><?= $keluarga->name; ?></td>
        </tr>
        <?php
            }
        }
        ?>
		</tbody>
        <br>
    </table>
    <?php
    }else{
    ?>
    
    <?php
    }
    ?>
</div>