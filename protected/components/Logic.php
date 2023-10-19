<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\components;

use app\models\Employee;
use app\models\EventRecognition;
use app\models\MvSummaryRecognitionPoint;
use app\models\Objective;
use yii;
use app\models\TrainingDevelopment;
use app\models\Education;
use app\models\Family;
use app\models\ContactPerson;
use app\models\PersonAddress;
use app\models\EmployeeHistory;
use app\models\GeneralDokumen;
use app\models\Event;
use app\models\GeneralEmployee;
use app\models\PerformanceHistory;
use app\models\KeyActionProgressEvidence;
use app\models\MonitoringCoachingMentoring;
use app\models\MonitoringPenilaianEvaluasi;
use app\models\KaryawanAkanPensiun;
use app\models\KaryawanPayslipTelkom;
use app\models\Spt;
use yii\helpers\Url;
use DateTime;
use app\components\Api;
use app\models\KaryawanAllColumn;
use app\models\MonitoringRecognitionPoint;
use app\models\SummaryRecognitionPoint;
use app\models\SptDokumen;
use Cocur\Slugify\Slugify;

class Logic
{
	const APP_STATUS_APPROVED = 'APPROVED';
	const APP_STATUS_ON_PROGRESS = 'ON PROGRESS';
	const APP_STATUS_SUBMIT = 'SUBMIT';
	const APP_STATUS_RETURN = 'RETURN';
	const APP_STATUS_REQUEST_DELETE = 'REQUEST DELETE';
	const APP_STATUS_DELETED = 'DELETED';
	
	private function _getStatusCodeMessage($status){
		$codes = Array(
			200 => 'OK',
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
		);
		
		return (isset($codes[$status])) ? $codes[$status] : '';
	}
	
	public function _sendResponse($code = 200, $status, $message, $hasil = NULL, $content_type = 'application/json'){
		if($content_type == 'application/json'){
			\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			 
			$status_header = 'HTTP/1.1 ' . $code . ' ' . Logic::_getStatusCodeMessage($code);
			header($status_header);
			header('Content-type: ' . $content_type);
			
			$data['code'] = $code;
			$data['status'] = $status;
			$data['message'] = $message;
			if(!empty($hasil)){
				$data['hasil'] = $hasil;
			}
			return $data;
		}else{
			$signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'] . ' Server at ' . $_SERVER['SERVER_NAME'] . ' Port ' . $_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];
			$data= '
			
			<html>
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
				<title>' . $status . ' ' . Logic::_getStatusCodeMessage($code) . '</title>
			</head>
			<body>
				<h1>' . Logic::_getStatusCodeMessage($code) . '</h1>
				<p>' . $message . '</p>
				<hr />
				<address>' . $signature . '</address>
			</body>
			</html>';
			return $data;
		}
	}
	
    public static function Exception($e)
    {
        $YII_ENV = getenv('YII_ENV') ? getenv('YII_ENV') : YII_ENV;
        if ($YII_ENV == 'dev') {
            $message = 'Terjadi kesalahan! Silahkan hubungi administrator. <div class="m-t-10">Detail stack trace : ' . $e . '</div>';
        } else {
            $message = 'Terjadi kesalahan! Silahkan hubungi administrator. ';
        }

        return '<div class="alert alert-danger mt-1 alert-validation-msg" role="alert"><div class="alert-body align-items-center">' . $message . ' </div></div>';
    }

    public static function Exceptionajax()
    {
        return 'This action for ajax request, please make sure you do ajax call';
    }

    public static function convertDate($date)
    {
        $dt = new DateTime($date);
        return $date ? $dt->format('d-m-Y') : null;
    }

    public static function convertDateManual($date)
    {
        if (!empty($date)) {
            $check = explode('-', $date);
            if (!empty($check)) {
                return date('d-m-Y', strtotime($date));
            } else {
                return date('d-m-Y', strtotime($date->format('d-m-Y')));
            }
        } else {
            return null;
        }
    }

    public static function dbFormatDate($date)
    {
        $dt = new DateTime($date);
        return $date ? $dt->format('Y-m-d') : null;
    }
	
	public static function statusActive()
    {
        return true;
    }
	
	public static function statusInActive()
    {
        return false;
    }

    /*
     * history approval
     * digunakan untuk tabel approval
     *
     * APPROVED => data disetujui oleh admin, dan dapat ditampilkan pada user
     * SUBMIT => data baru diajukan oleh user, belum disetujui oleh admin
     * REQUEST DELETE => data diajukan oleh user untuk dihapus, dan sedang menunggu persetujuan
     * RETURN => data tidak disetujui oleh admin, dan dikembalikan ke user
     */

    public static function statusApproval()
    {
        return ['APPROVED', 'RETURN', 'SUBMIT', 'REQUEST DELETE',];
    }

    /*
     * app_status pada tabel education, training_development, family,
     * person_address, contact_person
     *
     * APPROVED => data disetujui oleh admin, dan dapat ditampilkan pada user
     * ON PROGRESS => data masih menunggu persetujuan admin, dan user dapat diinfokan bahwa data perubahan masih menunggu persetujuan
     * DELETE => data diajukan oleh user untuk dihapus, dan telah disetujui oleh admin
     * RETURN => perubahan data diajukan oleh user, namun dikembalikan oleh admin ke user bersangkutan
     */

    public static function dataApproval()
    {
        return ['APPROVED', 'ON PROGRESS', 'DELETED', 'RETURN'];
    }

    public static function getIndoDate($date, $versi = null)
    {
        $dt = new DateTime($date);
        return $date ? $dt->format('d-m-Y') : '-';
    }

    function Timeago($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }
        $string = array_slice($string, 0, ($full) ? 2 : 1);

        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    /*
     * Dapat mengecek jika karyawan ybs memiliki multi roles
     */

    public function hasRole($role_name, $user_id)
    {
        $authManager = \Yii::$app->getAuthManager();
        return $authManager->getAssignment($role_name, $user_id) ? true : false;
    }

    public static function Downloadfile($path) {
        return Yii::$app->response->sendFile($path);
    }

    public static function DownloadfileRead($fullpath, $filename)
    {
        return Yii::$app->response->sendFile($fullpath, $filename, ['inline' => true]);
    }

    /*
     * Dapat mengecek jika karyawan ybs memiliki multi roles
     */

    public function hasApprovalProfile($jenis)
    {
        $order = null;
        $datalimit = null;
        if ($jenis == 'count') {
            $select = 'COUNT(*) total_all';
        } else {
            $select = 'a.*, b.description data_tab, c.employee_id';
            if ($jenis == 'datalimit') {
                $datalimit = 'LIMIT 5';
            }
            $order = 'ORDER BY created_time DESC';
        }

        $db = Yii::$app->db;
        $sqldata = $db->createCommand('WITH get_partisi AS(
			SELECT *, row_number() over (partition by data_id, apptype_id ORDER BY created_time desc) as no_partisi
			FROM approval
			ORDER BY created_time DESC
		)
		SELECT ' . $select . '
		FROM get_partisi a
		JOIN m_master b ON b.master_id = a.apptype_id AND b.key = \'approval_type\'
		JOIN employee c ON c.person_id = a.person_id_sender
		WHERE a.no_partisi = :no_partisi AND a.app_status IN (\'SUBMIT\',\'REQUEST DELETE\')
		' . $order . '
		' . $datalimit . '');
        $sqldata->bindValue(':no_partisi', 1);
        if ($jenis == 'count') {
            $data = $sqldata->queryOne();
        } else {
            $data = $sqldata->queryAll();
        }
        return $data;
    }

    public static function hasApprovalObjective($jenis, $person_id)
    {
        // $order = null;
        // $datalimit = null;
        // if ($jenis == 'count') {
            // $select = 'COUNT(*) total_all';
        // } else {
            // $select = 'a.*, b.description data_tab';
            // if ($jenis == 'datalimit') {
                // $datalimit = 'LIMIT 5';
            // }
            // $order = 'ORDER BY created_time DESC';
        // }

        // $db = Yii::$app->db;
        // $sqldata = $db->createCommand('WITH get_partisi AS(
			// SELECT *, row_number() over (partition by data_id, apptype_id ORDER BY created_time desc) as no_partisi
			// FROM approval
			// ORDER BY created_time DESC
		// )
		// SELECT ' . $select . '
		// FROM get_partisi a
		// inner join approval_type b on b.apptype_id = a.apptype_id
		// where b.apptype_id  = :p1 and a.person_id_approval = :p2 and a.no_partisi = :p3 and a.app_status in (\'SUBMIT\')
		// ' . $order . '
		// ' . $datalimit . '');
        // $sqldata->bindValue(':p1', '08');
        // $sqldata->bindValue(':p2', $person_id);
        // $sqldata->bindValue(':p3', 1);
        // if ($jenis == 'count') {
            // $data = $sqldata->queryOne();
        // } else {
            // $data = $sqldata->queryAll();
        // }
        return $data;
    }

    public static function hasFormEvaluasi($jenis, $person_id)
    {
        // $order = null;
        // $datalimit = null;
        // $person_id = strval($person_id);
        // if ($jenis == 'count') {
            // $select = 'COUNT(*) total_all';
        // } else {
            // $select = 'a.*, b.description data_tab';
            // if ($jenis == 'datalimit') {
                // $datalimit = 'LIMIT 5';
            // }
            // $order = 'ORDER BY created_time DESC';
        // }

        // $db = Yii::$app->db;
        // $sqldata = $db->createCommand('WITH get_partisi AS(
			// SELECT *, row_number() over (partition by data_id, apptype_id ORDER BY created_time desc) as no_partisi
			// FROM approval
			// ORDER BY created_time DESC
		// )
		// SELECT ' . $select . '
		// FROM get_partisi a
		// inner join approval_type b on b.apptype_id = a.apptype_id
		// where b.apptype_id  = :p1 and a.created_by = :p2 and a.no_partisi = :p3 and a.app_status in (\'SUBMIT\')
		// ' . $order . '
		// ' . $datalimit . '');
        // $sqldata->bindValue(':p1', 13);
        // $sqldata->bindValue(':p2', $person_id);
        // $sqldata->bindValue(':p3', 1);
        // if ($jenis == 'count') {
            // $data = $sqldata->queryOne();
        // } else {
            // $data = $sqldata->queryAll();
        // }
        return $data;
    }

    public static function hasApprovalKeyAction($jenis, $person_id)
    {
        // $order = null;
        // $datalimit = null;
        // if ($jenis == 'count') {
            // $select = 'COUNT(*) total_all';
        // } else {
            // $select = 'a.*, b.description data_tab';
            // if ($jenis == 'datalimit') {
                // $datalimit = 'LIMIT 5';
            // }
            // $order = 'ORDER BY created_time DESC';
        // }

        // $db = Yii::$app->db;
        // $sqldata = $db->createCommand('WITH get_partisi AS(
			// SELECT *, row_number() over (partition by data_id, apptype_id ORDER BY created_time desc) as no_partisi
			// FROM approval
			// ORDER BY created_time DESC
		// )
		// SELECT ' . $select . '
		// FROM get_partisi a
		// inner join approval_type b on b.apptype_id = a.apptype_id
		// where b.apptype_id  = :p1 and a.person_id_approval = :p2 and a.no_partisi = :p3 and a.app_status in (\'SUBMIT\')
		// ' . $order . '
		// ' . $datalimit . '');
        // $sqldata->bindValue(':p1', '10');
        // $sqldata->bindValue(':p2', $person_id);
        // $sqldata->bindValue(':p3', 1);
        // if ($jenis == 'count') {
            // $data = $sqldata->queryOne();
        // } else {
            // $data = $sqldata->queryAll();
        // }
        return $data;
    }

    public function hasNotif($jenis)
    {
        $superadmin = Logic::hasRole('superadmin', Yii::$app->user->identity->user_id);
        $adminprofile = Logic::hasRole('adminprofile', Yii::$app->user->identity->user_id);
        $data['data'] = [];
        if ($superadmin == true || $adminprofile == true) {
            if (Logic::hasApprovalProfile('count')['total_all'] > 0) {
                $data['data'][0]['jenis'] = 'Pengajuan Profile';
                $data['data'][0]['total_data'] = Logic::hasApprovalProfile('count')['total_all'];
                $data['data'][0]['hasil']['raw'] = Logic::hasApprovalProfile($jenis);
                if (!empty(Logic::hasApprovalProfile($jenis))) {
                    foreach (Logic::hasApprovalProfile($jenis) as $ldx => $lrow) {
                        $data['data'][0]['hasil']['normalisasi'][$ldx]['message'] = $lrow['employee_id'] . ' melakukan ' . $lrow['comment'];
                        $data['data'][0]['hasil']['normalisasi'][$ldx]['timeago'] = Logic::Timeago($lrow['created_time']);
                        $data['data'][0]['hasil']['normalisasi'][$ldx]['url'] = Url::to(['/verification/default/index']);
                    }
                }
            }
        }
		
        return $data;
    }

    public function array_multi_key_exists(array $arrNeedles, array $arrHaystack, $blnMatchAll = true)
    {
        $blnFound = array_key_exists(array_shift($arrNeedles), $arrHaystack);

        if ($blnFound && (count($arrNeedles) == 0 || !$blnMatchAll))
            return true;

        if (!$blnFound && count($arrNeedles) == 0 || $blnMatchAll)
            return false;

        return array_multi_key_exists($arrNeedles, $arrHaystack, $blnMatchAll);
    }

    public function getPhoto($url)
    {
        $dev = YII_ENV_DEV;
		$name_app = url::base();
		if(file_exists($url)){
			if ($url != '') {
				$exp = explode('/', $url);
				$revexp = array_reverse($exp);

				if ($dev == true) {
					$filename = $revexp[0];
					$foldername = $revexp[1];
					$nikname = $revexp[2];
					$folderstorage = $revexp[3];
					$url = $name_app . '/' . $folderstorage . '/' . $nikname . '/' . $foldername . '/' . $filename;
				} else {
					$filename = $revexp[0];
					$foldername = $revexp[1];
					$nikname = $revexp[2];
					$folderstorage = $revexp[3];
					if ($folderstorage != 'storage') {
						$url = $name_app. '/' . $nikname . '/' . $foldername . '/' . $filename;
					} else {
						$url = $name_app. '/' . $folderstorage . '/' . $nikname . '/' . $foldername . '/' . $filename;
					}
				}
			} else {
				$name_app = url::base();
				$url = $name_app . '/storage/nophoto.png';
			}
		}else{
			$url = $name_app . '/storage/nophoto.png';
		}	
		
		return $url;
    }

    public function getPensiun($date_of_birth)
    {
        return $date_of_birth ? date('Y-m-d', strtotime($date_of_birth . " + 56 year")) : null;
    }

    public function getMasaKerja($date_params)
    {
        $date_now = new \DateTime();
        $date_bekerja = new \DateTime($date_params);
        $masa_kerja = $date_bekerja->diff($date_now);

        return $masa_kerja->y . ' Tahun ' . $masa_kerja->m . ' Bulan';
    }

    public static function hasTahunPeriode()
    {
        $db = Yii::$app->db;
        $sqldata = $db->createCommand('WITH get_partisi AS(
			SELECT EXTRACT(\'year\' FROM tgl_aw_pengisian) tahun_pengisian, row_number() over (partition by EXTRACT(\'year\' FROM tgl_aw_pengisian) ORDER BY created_time desc) as no_partisi
			FROM event
			ORDER BY tgl_aw_pengisian DESC
		)
		SELECT a.*
		FROM get_partisi a
		WHERE no_partisi = :param1');
        $sqldata->bindValue(':param1', 1);

        $arrtahun = [];
        foreach ($sqldata->queryAll() as $sdx => $srow) {
            $arrtahun[$srow['tahun_pengisian']] = $srow['tahun_pengisian'];
        }

        return $arrtahun;
    }

    public static function hasEventPeriode($tahun)
    {
        $db = Yii::$app->db;
        $sqldata = $db->createCommand('SELECT event_id, evt_name, tgl_aw_pengisian, tgl_ak_pengisian
		FROM event
		WHERE EXTRACT(\'year\' FROM tgl_aw_pengisian) = :tahun
		ORDER BY tgl_aw_pengisian DESC');
        $sqldata->bindParam(':tahun', $tahun);

        return $sqldata->queryAll();
    }

    public static function getDokumenCoaching($kap_id)
    {
        $dataall = KeyActionProgressEvidence::find()
            ->andWhere('kap_id = :kap_id', [':kap_id' => $kap_id])
            ->all();
        $dokumen = '';
        if (!empty($dataall)) {
            foreach ($dataall as $ddx => $drow) {
				if(file_exists($drow->url_scan_progress)){
					$dokumen .= '<a title="Download File" href="' . Url::toRoute(['/profile/default/downloadfile', 'path' => $drow->url_scan_progress]) . '"><i class="icofont icofont-file-document icofont-2x"></i></a>';
				}
            }
        }

        return $dokumen;
    }

    public static function hasLastPencapaian($ka_id)
    {
        $db = Yii::$app->db;
        $sqldata = $db->createCommand('SELECT a.* , b.target
		FROM key_action_progress a
		JOIN key_action b ON b.ka_id = a.ka_id
		WHERE a.ka_id = :ka_id
		ORDER BY a.created_time DESC
		LIMIT 1');
        $sqldata->bindParam(':ka_id', $ka_id);

        return $sqldata->queryOne();
    }

    public static function monitoringSheetOne($jenis, $model, $dataarr)
    {
        if ($jenis == 'header') {
            $data = [
                'No',
                $model->getAttributeLabel('tahun_periode'),
                $model->getAttributeLabel('event_name'),
                $model->getAttributeLabel('tgl_aw_pengisian'),
                $model->getAttributeLabel('tgl_ak_pengisian'),
                $model->getAttributeLabel('tgl_aw_penilaian'),
                $model->getAttributeLabel('tgl_ak_penilaian'),
                $model->getAttributeLabel('goal_task_driven_name'),
                $model->getAttributeLabel('employee_id_creator') . ' Atasan',
                $model->getAttributeLabel('person_name_creator') . ' Atasan',
                $model->getAttributeLabel('objectunit_name_creator') . ' Atasan',
                $model->getAttributeLabel('object_name_creator') . ' Atasan',
                $model->getAttributeLabel('objective_key_program_name'),
                $model->getAttributeLabel('key_result_name'),
                $model->getAttributeLabel('ka_name'),
                $model->getAttributeLabel('employee_id_assign') . ' Bawahan',
                $model->getAttributeLabel('person_name_assign') . ' Bawahan',
                $model->getAttributeLabel('object_name_assign') . ' Bawahan',
                $model->getAttributeLabel('objectunit_name_assign') . ' Bawahan',
                $model->getAttributeLabel('ka_point_kompleksitas'),
                $model->getAttributeLabel('ka_target'),
                $model->getAttributeLabel('ka_pencapaian'),
                $model->getAttributeLabel('ka_satuan')
            ];
        } else {
            $dataall = MonitoringCoachingMentoring::find()
                ->andWhere(LTRIM($dataarr['search_single'], ' AND '), $dataarr['param_single'])
                ->andWhere($dataarr['imp_obj'], $dataarr['params_obj'])
                ->andWhere($dataarr['imp_key_result'], $dataarr['params_key_result'])
                ->andWhere($dataarr['imp_ka'], $dataarr['params_key_action'])
                ->andWhere($dataarr['imp_unit_creator'], $dataarr['params_unit_creator'])
                ->andWhere($dataarr['imp_employee_assign'], $dataarr['params_employee_assign'])
                ->andWhere($dataarr['imp_status_ka'])
                ->all();

            if (!empty($dataall)) {
                $i = 1;
                foreach ($dataall as $index => $value) {
                    $data[$index][] = $i;
                    $data[$index][] = $value->tahun_periode;
                    $data[$index][] = $value->event_name;
                    $data[$index][] = $value->tgl_aw_pengisian;
                    $data[$index][] = $value->tgl_ak_pengisian;
                    $data[$index][] = $value->tgl_aw_penilaian;
                    $data[$index][] = $value->tgl_ak_penilaian;
                    $data[$index][] = $value->goal_task_driven_name;
                    $data[$index][] = $value->employee_id_creator;
                    $data[$index][] = $value->person_name_creator;
                    $data[$index][] = $value->objectunit_name_creator;
                    $data[$index][] = $value->object_name_creator;
                    $data[$index][] = $value->objective_key_program_name;
                    $data[$index][] = $value->key_result_name;
                    $data[$index][] = $value->ka_name;
                    $data[$index][] = $value->employee_id_assign;
                    $data[$index][] = $value->person_name_assign;
                    $data[$index][] = $value->object_name_assign;
                    $data[$index][] = $value->objectunit_name_assign;
                    $data[$index][] = $value->ka_point_kompleksitas;
                    $data[$index][] = $value->ka_target;
                    $data[$index][] = $value->ka_pencapaian;
                    $data[$index][] = $value->ka_satuan;

                    $i++;
                }
            }
        }

        return $data;
    }

    public static function monitoringSheetTwo($jenis, $model, $dataarr)
    {
        if ($jenis == 'header') {
            $data = [
                'No',
                $model->getAttributeLabel('tahun_periode'),
                $model->getAttributeLabel('event_name'),
                $model->getAttributeLabel('tgl_aw_pengisian'),
                $model->getAttributeLabel('tgl_ak_pengisian'),
                $model->getAttributeLabel('tgl_aw_penilaian'),
                $model->getAttributeLabel('tgl_ak_penilaian'),
                $model->getAttributeLabel('goal_task_driven_name'),
                $model->getAttributeLabel('employee_id_creator') . ' Atasan',
                $model->getAttributeLabel('person_name_creator') . ' Atasan',
                $model->getAttributeLabel('objectunit_name_creator') . ' Atasan',
                $model->getAttributeLabel('object_name_creator') . ' Atasan',
                $model->getAttributeLabel('objective_key_program_name'),
                $model->getAttributeLabel('key_result_name'),
                $model->getAttributeLabel('ka_name'),
                $model->getAttributeLabel('employee_id_assign') . ' Bawahan',
                $model->getAttributeLabel('person_name_assign') . ' Bawahan',
                $model->getAttributeLabel('object_name_assign') . ' Bawahan',
                $model->getAttributeLabel('objectunit_name_assign') . ' Bawahan',
                $model->getAttributeLabel('ka_point_kompleksitas'),
                $model->getAttributeLabel('ka_target'),
                $model->getAttributeLabel('ka_pencapaian'),
                'Progress Pencapaian',
                $model->getAttributeLabel('ka_satuan'),
                'Tanggal Progress'
            ];
        } else {
            $dataall = MonitoringCoachingMentoring::find()
                ->alias('a')
                ->select('a.tahun_periode, a.event_name, a.tgl_aw_pengisian, a.tgl_ak_pengisian, a.tgl_aw_penilaian, a.tgl_ak_penilaian, a.employee_id_creator, a.person_name_creator, a.object_name_creator, a.objectunit_name_creator, a.employee_id_assign, a.person_name_assign, a.object_name_assign, a.objectunit_name_assign, a.goal_task_driven_name, a.objective_key_program_name, a.key_result_name, a.ka_name, a.ka_point_kompleksitas, a.ka_target, a.ka_pencapaian, b.kap_progress, a.ka_satuan,b.created_time')
                ->innerJoin('key_action_progress b', 'b.ka_id = a.ka_id')
                ->andWhere(LTRIM($dataarr['search_single'], ' AND '), $dataarr['param_single'])
                ->andWhere($dataarr['imp_obj'], $dataarr['params_obj'])
                ->andWhere($dataarr['imp_key_result'], $dataarr['params_key_result'])
                ->andWhere($dataarr['imp_ka'], $dataarr['params_key_action'])
                ->andWhere($dataarr['imp_unit_creator'], $dataarr['params_unit_creator'])
                ->andWhere($dataarr['imp_employee_assign'], $dataarr['params_employee_assign'])
                ->andWhere($dataarr['imp_status_ka'])
                ->orderBy([
                    'a.employee_id_assign' => SORT_ASC,
                    'a.ka_id' => SORT_ASC,
                    'b.created_time' => SORT_DESC
                ])
                ->all();

            if (!empty($dataall)) {
                $i = 1;
                foreach ($dataall as $index => $value) {
                    $data[$index][] = $i;
                    $data[$index][] = $value->tahun_periode;
                    $data[$index][] = $value->event_name;
                    $data[$index][] = $value->tgl_aw_pengisian;
                    $data[$index][] = $value->tgl_ak_pengisian;
                    $data[$index][] = $value->tgl_aw_penilaian;
                    $data[$index][] = $value->tgl_ak_penilaian;
                    $data[$index][] = $value->goal_task_driven_name;
                    $data[$index][] = $value->employee_id_creator;
                    $data[$index][] = $value->person_name_creator;
                    $data[$index][] = $value->objectunit_name_creator;
                    $data[$index][] = $value->object_name_creator;
                    $data[$index][] = $value->objective_key_program_name;
                    $data[$index][] = $value->key_result_name;
                    $data[$index][] = $value->ka_name;
                    $data[$index][] = $value->employee_id_assign;
                    $data[$index][] = $value->person_name_assign;
                    $data[$index][] = $value->object_name_assign;
                    $data[$index][] = $value->objectunit_name_assign;
                    $data[$index][] = $value->ka_point_kompleksitas;
                    $data[$index][] = $value->ka_target;
                    $data[$index][] = $value->ka_pencapaian;
                    $data[$index][] = $value->kap_progress;
                    $data[$index][] = $value->ka_satuan;
                    $data[$index][] = $value->created_time;

                    $i++;
                }
            }
        }

        return $data;
    }

    public static function monitoringSheetThree($jenis, $model, $dataarr)
    {
        if ($jenis == 'header') {
            $data = [
                'No',
                $model->getAttributeLabel('tahun_periode'),
                $model->getAttributeLabel('event_name'),
                $model->getAttributeLabel('tgl_aw_pengisian'),
                $model->getAttributeLabel('tgl_ak_pengisian'),
                $model->getAttributeLabel('tgl_aw_penilaian'),
                $model->getAttributeLabel('tgl_ak_penilaian'),
                $model->getAttributeLabel('goal_task_driven_name'),
                $model->getAttributeLabel('employee_id_creator') . ' Atasan',
                $model->getAttributeLabel('person_name_creator') . ' Atasan',
                $model->getAttributeLabel('objectunit_name_creator') . ' Atasan',
                $model->getAttributeLabel('object_name_creator') . ' Atasan',
                $model->getAttributeLabel('objective_key_program_name'),
                $model->getAttributeLabel('key_result_name'),
                $model->getAttributeLabel('ka_name'),
                $model->getAttributeLabel('employee_id_assign') . ' Bawahan',
                $model->getAttributeLabel('person_name_assign') . ' Bawahan',
                $model->getAttributeLabel('object_name_assign') . ' Bawahan',
                $model->getAttributeLabel('objectunit_name_assign') . ' Bawahan',
                $model->getAttributeLabel('ka_point_kompleksitas'),
                $model->getAttributeLabel('ka_target'),
                $model->getAttributeLabel('ka_pencapaian'),
                $model->getAttributeLabel('ka_satuan'),
                'Komentar',
                'Nik Pengirim',
                'Nama Pengirim',
                'Tanggal Kirim'
            ];
        } else {
            $dataall = MonitoringCoachingMentoring::find()
                ->alias('a')
                ->select('a.tahun_periode, a.event_name, a.tgl_aw_pengisian, a.tgl_ak_pengisian, a.tgl_aw_penilaian, a.tgl_ak_penilaian, a.employee_id_creator, a.person_name_creator, a.object_name_creator, a.objectunit_name_creator, a.employee_id_assign, a.person_name_assign, a.object_name_assign, a.objectunit_name_assign, a.goal_task_driven_name, a.objective_key_program_name, a.key_result_name, a.ka_name, a.ka_point_kompleksitas, a.ka_target, a.ka_pencapaian, b.coaching_note, c.employee_id nik_pengirim, c.person_name nama_pengirim, a.ka_satuan, b.created_time')
                ->innerJoin('key_action_note b', 'b.ka_id = a.ka_id')
                ->innerJoin('employee c', 'c.person_id = b.person_id_sender')
                ->andWhere(LTRIM($dataarr['search_single'], ' AND '), $dataarr['param_single'])
                ->andWhere($dataarr['imp_obj'], $dataarr['params_obj'])
                ->andWhere($dataarr['imp_key_result'], $dataarr['params_key_result'])
                ->andWhere($dataarr['imp_ka'], $dataarr['params_key_action'])
                ->andWhere($dataarr['imp_unit_creator'], $dataarr['params_unit_creator'])
                ->andWhere($dataarr['imp_employee_assign'], $dataarr['params_employee_assign'])
                ->andWhere($dataarr['imp_status_ka'])
                ->orderBy([
                    'a.employee_id_assign' => SORT_ASC,
                    'a.ka_id' => SORT_ASC,
                    'b.created_time' => SORT_DESC
                ])
                ->all();

            if (!empty($dataall)) {
                $i = 1;
                foreach ($dataall as $index => $value) {
                    $data[$index][] = $i;
                    $data[$index][] = $value->tahun_periode;
                    $data[$index][] = $value->event_name;
                    $data[$index][] = $value->tgl_aw_pengisian;
                    $data[$index][] = $value->tgl_ak_pengisian;
                    $data[$index][] = $value->tgl_aw_penilaian;
                    $data[$index][] = $value->tgl_ak_penilaian;
                    $data[$index][] = $value->goal_task_driven_name;
                    $data[$index][] = $value->employee_id_creator;
                    $data[$index][] = $value->person_name_creator;
                    $data[$index][] = $value->objectunit_name_creator;
                    $data[$index][] = $value->object_name_creator;
                    $data[$index][] = $value->objective_key_program_name;
                    $data[$index][] = $value->key_result_name;
                    $data[$index][] = $value->ka_name;
                    $data[$index][] = $value->employee_id_assign;
                    $data[$index][] = $value->person_name_assign;
                    $data[$index][] = $value->object_name_assign;
                    $data[$index][] = $value->objectunit_name_assign;
                    $data[$index][] = $value->ka_point_kompleksitas;
                    $data[$index][] = $value->ka_target;
                    $data[$index][] = $value->ka_pencapaian;
                    $data[$index][] = $value->ka_satuan;
                    $data[$index][] = $value->coaching_note;
                    $data[$index][] = $value->nik_pengirim;
                    $data[$index][] = $value->nama_pengirim;
                    $data[$index][] = $value->created_time;

                    $i++;
                }
            }
        }

        return $data;
    }

    public static function hasPenilaian($event_id = null, $jenis)
    {
        if ($event_id == null) {
            $where = 'tgl_aw_pengisian <= CURRENT_DATE AND tgl_ak_pengisian >= CURRENT_DATE';
        } else {
            $where = 'event_id = :event_id';
        }

        $db = Yii::$app->db;
        $sqldata = $db->createCommand('SELECT event_id, evt_name, tgl_aw_pengisian, tgl_ak_pengisian, tgl_aw_penilaian, tgl_ak_penilaian
		FROM event
		WHERE ' . $where . '
		');
        if ($event_id != null) {
            $sqldata->bindParam(':event_id', $event_id);
        }

        $data = [];
        $dataevent = $sqldata->queryOne();
        if (!empty($dataevent)) {
            if ($jenis == 'pengisian') {
                if ($dataevent['tgl_aw_pengisian'] <= date('Y-m-d') && $dataevent['tgl_ak_pengisian'] >= date('Y-m-d')) {
                    $status = true;
                    $message = '<div class="alert alert-info background-info"><i class="icofont icofont-warning-alt"></i> Pemberitahuan
						<ol>
							<li>Periode pengisian ' . $dataevent['evt_name'] . ' sedang berlangsung.</li>
							<li>Periode pengisian berlangsung selama ' . Logic::getIndoDate($dataevent['tgl_aw_pengisian']) . ' s/d ' . Logic::getIndoDate($dataevent['tgl_ak_pengisian']) . '</li>
							<li>Anda dapat melakukan pengisian komentar ataupun pengisian progress pencapaian selama periode pengisian berlangsung</li>
						</ol>
					</div>';
                } else if ($dataevent['tgl_ak_pengisian'] <= date('Y-m-d')) {
                    $status = false;
                    $message = '<div class="alert alert-danger background-danger"><i class="icofont icofont-warning-alt"></i> Pemberitahuan
						<ol>
							<li>Periode pengisian ' . $dataevent['evt_name'] . ' sudah berakhir.</li>
							<li>Periode pengisian sudah berakhir pada jangka waktu ' . Logic::getIndoDate($dataevent['tgl_aw_pengisian']) . ' s/d ' . Logic::getIndoDate($dataevent['tgl_ak_pengisian']) . '</li>
							<li>Anda tidak dapat melakukan pengisian komentar ataupun pengisian progress pencapaian jika periode sudah berakhir namun anda tetap bisa melihat histori yang sudah pernah dibuat</li>
						</ol>
					</div>';
                }
            } else {
                if ($dataevent['tgl_aw_penilaian'] <= date('Y-m-d') && $dataevent['tgl_ak_penilaian'] >= date('Y-m-d')) {
                    $status = true;
                    $message = '<div class="alert alert-info background-info"><i class="icofont icofont-warning-alt"></i> Pemberitahuan
						<ol>
							<li>Periode penilaian ' . $dataevent['evt_name'] . ' sedang berlangsung.</li>
							<li>Periode penilaian berlangsung selama ' . Logic::getIndoDate($dataevent['tgl_aw_penilaian']) . ' s/d ' . Logic::getIndoDate($dataevent['tgl_ak_penilaian']) . '</li>
							<li>Anda dapat melakukan penilaian / evaluasi terhadap bawahan anda selama periode berlangsung</li>
						</ol>
					</div>';
                } else if ($dataevent['tgl_aw_penilaian'] >= date('Y-m-d')) {
                    $status = true;
                    $message = '<div class="alert alert-warning background-warning"><i class="icofont icofont-warning-alt"></i> Pemberitahuan
						<ol>
							<li>Periode penilaian ' . $dataevent['evt_name'] . ' belum dimulai.</li>
							<li>Periode penilaian akan dimulai pada jangka waktu ' . Logic::getIndoDate($dataevent['tgl_aw_penilaian']) . ' s/d ' . Logic::getIndoDate($dataevent['tgl_ak_penilaian']) . '</li>
							<li>Anda tidak dapat melakukan penilaian / evaluasi terhadap bawahan anda selama periode belum dimulai</li>
						</ol>
					</div>';
                } else if ($dataevent['tgl_ak_penilaian'] <= date('Y-m-d')) {
                    $status = true;
                    $message = '<div class="alert alert-danger background-danger"><i class="icofont icofont-warning-alt"></i> Pemberitahuan
						<ol>
							<li>Periode penilaian ' . $dataevent['evt_name'] . ' sudah berakhir.</li>
							<li>Periode penilaian sudah berahir pada jangka waktu ' . Logic::getIndoDate($dataevent['tgl_aw_penilaian']) . ' s/d ' . Logic::getIndoDate($dataevent['tgl_ak_penilaian']) . '</li>
							<li>Anda tidak dapat melakukan penilaian / evaluasi terhadap bawahan anda jika periode sudah berakhir</li>
						</ol>
					</div>';
                }
            }
        }
        $data = [
            'status' => $status,
            'message' => $message
        ];

        return $data;
    }

    public static function monitoringSheetOnePe($jenis, $model, $dataarr)
    {
        if ($jenis == 'header') {
            $data = [
                'No',
                $model->getAttributeLabel('tahun_periode'),
                $model->getAttributeLabel('event_name'),
                $model->getAttributeLabel('tgl_aw_pengisian'),
                $model->getAttributeLabel('tgl_ak_pengisian'),
                $model->getAttributeLabel('tgl_aw_penilaian'),
                $model->getAttributeLabel('tgl_ak_penilaian'),
                $model->getAttributeLabel('goal_task_driven_name'),
                $model->getAttributeLabel('employee_id_creator') . ' Atasan',
                $model->getAttributeLabel('person_name_creator') . ' Atasan',
                $model->getAttributeLabel('objectunit_name_creator') . ' Atasan',
                $model->getAttributeLabel('object_name_creator') . ' Atasan',
                $model->getAttributeLabel('objective_key_program_name'),
                $model->getAttributeLabel('key_result_name'),
                $model->getAttributeLabel('ka_name'),
                $model->getAttributeLabel('employee_id_assign') . ' Bawahan',
                $model->getAttributeLabel('person_name_assign') . ' Bawahan',
                $model->getAttributeLabel('object_name_assign') . ' Bawahan',
                $model->getAttributeLabel('objectunit_name_assign') . ' Bawahan',
                $model->getAttributeLabel('ka_point_kompleksitas'),
                $model->getAttributeLabel('ka_target'),
                $model->getAttributeLabel('ka_pencapaian'),
                $model->getAttributeLabel('ka_satuan'),
                $model->getAttributeLabel('nilai_pencapaian'),
                $model->getAttributeLabel('rating'),
                $model->getAttributeLabel('status_penilaian'),
                $model->getAttributeLabel('nilai_adjustment'),
                $model->getAttributeLabel('nilai_total')
            ];
        } else {
            $dataall = MonitoringPenilaianEvaluasi::find()
                ->andWhere(LTRIM($dataarr['search_single'], ' AND '), $dataarr['param_single'])
                ->andWhere($dataarr['imp_obj'], $dataarr['params_obj'])
                ->andWhere($dataarr['imp_key_result'], $dataarr['params_key_result'])
                ->andWhere($dataarr['imp_ka'], $dataarr['params_key_action'])
                ->andWhere($dataarr['imp_unit_creator'], $dataarr['params_unit_creator'])
                ->andWhere($dataarr['imp_employee_assign'], $dataarr['params_employee_assign'])
                ->andWhere($dataarr['imp_status_ka'])
                ->all();

            if (!empty($dataall)) {
                $i = 1;
                foreach ($dataall as $index => $value) {
                    $data[$index][] = $i;
                    $data[$index][] = $value->tahun_periode;
                    $data[$index][] = $value->event_name;
                    $data[$index][] = $value->tgl_aw_pengisian;
                    $data[$index][] = $value->tgl_ak_pengisian;
                    $data[$index][] = $value->tgl_aw_penilaian;
                    $data[$index][] = $value->tgl_ak_penilaian;
                    $data[$index][] = $value->goal_task_driven_name;
                    $data[$index][] = $value->employee_id_creator;
                    $data[$index][] = $value->person_name_creator;
                    $data[$index][] = $value->objectunit_name_creator;
                    $data[$index][] = $value->object_name_creator;
                    $data[$index][] = $value->objective_key_program_name;
                    $data[$index][] = $value->key_result_name;
                    $data[$index][] = $value->ka_name;
                    $data[$index][] = $value->employee_id_assign;
                    $data[$index][] = $value->person_name_assign;
                    $data[$index][] = $value->object_name_assign;
                    $data[$index][] = $value->objectunit_name_assign;
                    $data[$index][] = $value->ka_point_kompleksitas;
                    $data[$index][] = $value->ka_target;
                    $data[$index][] = $value->ka_pencapaian;
                    $data[$index][] = $value->ka_satuan;
                    $data[$index][] = $value->nilai_pencapaian == NULL ? '' : $value->nilai_pencapaian . '%';
                    $data[$index][] = $value->rating;
                    $data[$index][] = $value->status_penilaian == 1 ? 'Sudah dinilai' : 'Belum dinilai';
                    $data[$index][] = $value->nilai_adjustment;
                    $data[$index][] = $value->nilai_total;

                    $i++;
                }
            }
        }

        return $data;
    }

    public static function monitoringSheetTwoPe($jenis, $model, $dataarr)
    {
        if ($jenis == 'header') {
            $data = [
                'No',
                $model->getAttributeLabel('tahun_periode'),
                $model->getAttributeLabel('event_name'),
                $model->getAttributeLabel('tgl_aw_pengisian'),
                $model->getAttributeLabel('tgl_ak_pengisian'),
                $model->getAttributeLabel('tgl_aw_penilaian'),
                $model->getAttributeLabel('tgl_ak_penilaian'),
                $model->getAttributeLabel('goal_task_driven_name'),
                $model->getAttributeLabel('employee_id_creator') . ' Atasan',
                $model->getAttributeLabel('person_name_creator') . ' Atasan',
                $model->getAttributeLabel('objectunit_name_creator') . ' Atasan',
                $model->getAttributeLabel('object_name_creator') . ' Atasan',
                $model->getAttributeLabel('objective_key_program_name'),
                $model->getAttributeLabel('key_result_name'),
                $model->getAttributeLabel('ka_name'),
                $model->getAttributeLabel('employee_id_assign') . ' Bawahan',
                $model->getAttributeLabel('person_name_assign') . ' Bawahan',
                $model->getAttributeLabel('object_name_assign') . ' Bawahan',
                $model->getAttributeLabel('objectunit_name_assign') . ' Bawahan',
                $model->getAttributeLabel('ka_point_kompleksitas'),
                $model->getAttributeLabel('ka_target'),
                $model->getAttributeLabel('ka_pencapaian'),
                $model->getAttributeLabel('ka_satuan'),
                $model->getAttributeLabel('nilai_pencapaian'),
                $model->getAttributeLabel('status_penilaian'),
                $model->getAttributeLabel('komponen'),
                $model->getAttributeLabel('nilai_rating'),
                $model->getAttributeLabel('average_rating'),
                $model->getAttributeLabel('pembulatan_rating'),
                $model->getAttributeLabel('pengali_rating'),
                $model->getAttributeLabel('komentar'),
                $model->getAttributeLabel('nilai_adjustment'),
                $model->getAttributeLabel('nilai_total'),
                'Tanggal Penilaian'
            ];
        } else {
            $dataall = MonitoringPenilaianEvaluasi::find()
                ->alias('a')
                ->select('a.tahun_periode, a.event_name, a.tgl_aw_pengisian, a.tgl_ak_pengisian, a.tgl_aw_penilaian, a.tgl_ak_penilaian, a.employee_id_creator, a.person_name_creator, a.object_name_creator, a.objectunit_name_creator, a.employee_id_assign, a.person_name_assign, a.object_name_assign, a.objectunit_name_assign, a.goal_task_driven_name, a.objective_key_program_name, a.key_result_name, a.ka_name, a.ka_point_kompleksitas, a.ka_target, a.ka_pencapaian, a.ka_satuan, a.nilai_pencapaian, a.status_penilaian, c.description komponen, b.nilai_rating, b.average_rating, b.pembulatan_rating, b.pengali_rating, b.komentar, a.nilai_adjustment, a.nilai_total, b.created_time')
                ->innerJoin('key_action_evaluasi b', 'b.ka_id = a.ka_id')
                ->innerJoin('component_rating c', 'c.comprat_id = b.comprat_id')
                ->andWhere(LTRIM($dataarr['search_single'], ' AND '), $dataarr['param_single'])
                ->andWhere($dataarr['imp_obj'], $dataarr['params_obj'])
                ->andWhere($dataarr['imp_key_result'], $dataarr['params_key_result'])
                ->andWhere($dataarr['imp_ka'], $dataarr['params_key_action'])
                ->andWhere($dataarr['imp_unit_creator'], $dataarr['params_unit_creator'])
                ->andWhere($dataarr['imp_employee_assign'], $dataarr['params_employee_assign'])
                ->andWhere($dataarr['imp_status_ka'])
                ->orderBy([
                    'a.employee_id_assign' => SORT_ASC,
                    'a.ka_id' => SORT_ASC,
                    'c.comprat_id' => SORT_ASC,
                    'b.created_time' => SORT_DESC
                ])
                ->all();

            if (!empty($dataall)) {
                $i = 1;
                foreach ($dataall as $index => $value) {
                    $data[$index][] = $i;
                    $data[$index][] = $value->tahun_periode;
                    $data[$index][] = $value->event_name;
                    $data[$index][] = $value->tgl_aw_pengisian;
                    $data[$index][] = $value->tgl_ak_pengisian;
                    $data[$index][] = $value->tgl_aw_penilaian;
                    $data[$index][] = $value->tgl_ak_penilaian;
                    $data[$index][] = $value->goal_task_driven_name;
                    $data[$index][] = $value->employee_id_creator;
                    $data[$index][] = $value->person_name_creator;
                    $data[$index][] = $value->objectunit_name_creator;
                    $data[$index][] = $value->object_name_creator;
                    $data[$index][] = $value->objective_key_program_name;
                    $data[$index][] = $value->key_result_name;
                    $data[$index][] = $value->ka_name;
                    $data[$index][] = $value->employee_id_assign;
                    $data[$index][] = $value->person_name_assign;
                    $data[$index][] = $value->object_name_assign;
                    $data[$index][] = $value->objectunit_name_assign;
                    $data[$index][] = $value->ka_point_kompleksitas;
                    $data[$index][] = $value->ka_target;
                    $data[$index][] = $value->ka_pencapaian;
                    $data[$index][] = $value->ka_satuan;
                    $data[$index][] = $value->nilai_pencapaian . '%';
                    $data[$index][] = $value->status_penilaian == 1 ? 'Sudah dinilai' : 'Belum dinilai';
                    $data[$index][] = $value->komponen;
                    $data[$index][] = $value->nilai_rating;
                    $data[$index][] = $value->average_rating;
                    $data[$index][] = $value->pembulatan_rating;
                    $data[$index][] = $value->pengali_rating;
                    $data[$index][] = $value->komentar;
                    $data[$index][] = $value->nilai_adjustment;
                    $data[$index][] = $value->nilai_total;
                    $data[$index][] = $value->created_time;

                    $i++;
                }
            }
        }

        return $data;
    }

    public function akanPensiun($jenis)
    {
        if ($jenis == 'count') {
            $select = 'COUNT(*) total_all';
        } else {
            $select = '*';
            if ($jenis == 'datalimit') {
                $datalimit = 'LIMIT 5';
            }
            $order = 'ORDER BY tgl_perkiraan_pensiun DESC';
        }

        $db = Yii::$app->db;
        $sqldata = $db->createCommand('WITH "get_perkiraan_pensiun_karyawan" AS (
			SELECT person_id, employee_id, person_name, emsubgr_id, date_of_birth,  DATE((date_of_birth + INTERVAL \'56 YEAR\') - INTERVAL \'1 month\') tgl_reminder_pensiun, DATE(date_of_birth + INTERVAL \'56 YEAR\') tgl_perkiraan_pensiun
			FROM employee
		)
		SELECT ' . $select . '
		FROM get_perkiraan_pensiun_karyawan
		WHERE emsubgr_id = :param1 AND tgl_reminder_pensiun <= CURRENT_DATE AND tgl_perkiraan_pensiun >= CURRENT_DATE
		' . $order . '
		' . $datalimit . '');
        $sqldata->bindValue(':param1', '01');
        if ($jenis == 'count') {
            $data = $sqldata->queryOne();
        } else {
            $data = $sqldata->queryAll();
        }

        return $data;
    }

    public function tahunPensiun()
    {
        $db = Yii::$app->db;
        $sqldata = $db->createCommand('SELECT date_part(\'year\', CURRENT_DATE) tahun_current, tahun_pensiun
		FROM karyawan_akan_pensiun
		ORDER BY tahun_pensiun DESC
		LIMIT 1');
        $tahun = $sqldata->queryOne();
        for ($tahun['tahun_current']; $tahun['tahun_current'] <= $tahun['tahun_pensiun']; $tahun['tahun_current']++) {
            $datatahun[$tahun['tahun_current']] = $tahun['tahun_current'];
        }

        return $datatahun;
    }

    public function bulanPensiun()
    {
        $i = 1;
        for ($i = 1; $i <= 12; $i++) {
            $databulan[$i] = $i;
        }

        return $databulan;
    }

    public static function monitoringKaryawanAkanPensiun($jenis, $model, $dataarr)
    {
        if ($jenis == 'header') {
            $data = [
                'No',
                $model->getAttributeLabel('employee_id'),
                $model->getAttributeLabel('person_name'),
                $model->getAttributeLabel('band_name'),
                $model->getAttributeLabel('object_name'),
                $model->getAttributeLabel('objectunit_name'),
                $model->getAttributeLabel('person_area_name'),
                $model->getAttributeLabel('emsubgr_name'),
                $model->getAttributeLabel('tgl_lahir'),
                $model->getAttributeLabel('tgl_perkiraan_pensiun')
            ];
        } else {
            $dataall = KaryawanAkanPensiun::find()
                ->andWhere($dataarr['search_tahun_awal'], $dataarr['params_tahun_awal'])
                ->andWhere($dataarr['search_tahun_akhir'], $dataarr['params_tahun_akhir'])
                ->andWhere($dataarr['search_bulan_awal'], $dataarr['params_bulan_awal'])
                ->andWhere($dataarr['search_bulan_akhir'], $dataarr['params_bulan_akhir'])
                ->andWhere($dataarr['imp_employee_assign'], $dataarr['params_employee_assign'])
                ->all();

            if (!empty($dataall)) {
                $i = 1;
                foreach ($dataall as $index => $value) {
                    $data[$index][] = $i;
                    $data[$index][] = $value->employee_id;
                    $data[$index][] = $value->person_name;
                    $data[$index][] = $value->band_name;
                    $data[$index][] = $value->object_name;
                    $data[$index][] = $value->objectunit_name;
                    $data[$index][] = $value->person_area_name;
                    $data[$index][] = $value->emsubgr_name;
                    $data[$index][] = $value->tgl_lahir;
                    $data[$index][] = $value->tgl_perkiraan_pensiun;

                    $i++;
                }
            }
        }

        return $data;
    }

    public static function getPointkompleksitas(): array
    {
        return [5 => 5, 10 => 10, 15 => 15, 20 => 20, 25 => 25, 30 => 30, 35 => 35];
    }

    public static function getSatuan()
    {
        $qry = Yii::$app->db->createCommand("select distinct satuan from key_action ka where satuan is not null order by satuan ")->queryAll();
        $result = null;
        foreach ($qry as $row) {
            $result[$row['satuan']] = $row['satuan'];
        }
        return $result;
    }

    public static function paySlipTab($nik, $tahun_awal, $bulan_awal, $tahun_akhir, $bulan_akhir, $emptelgroup_id)
    {
        if (!empty($tahun_awal)) {
            if ($tahun_awal > date('Y') || $tahun_awal < date('Y')) {
                $datatahun_awal = date('Y');
            } else {
                $datatahun_awal = $tahun_awal;
            }
        } else {
            $datatahun_awal = date('Y');
        }

        if (!empty($tahun_akhir)) {
            if ($tahun_akhir > date('Y') || $tahun_akhir < date('Y')) {
                $datatahun_akhir = date('Y');
            } else {
                $datatahun_akhir = $tahun_akhir;
            }
        } else {
            $datatahun_akhir = date('Y');
        }

        if (!empty($bulan_awal)) {
            if ($bulan_awal >= 1 || $bulan_awal <= 12) {
                $databulan_awal = $bulan_awal;
            } else {
                $databulan_awal = 1;
            }
        } else {
            $databulan_awal = 1;
        }

        if (!empty($bulan_akhir)) {
            if ($bulan_akhir >= 1 || $bulan_akhir <= 12) {
                $databulan_akhir = $bulan_akhir;
            } else {
                $databulan_akhir = 12;
            }
        } else {
            $databulan_akhir = 12;
        }

        $periode_awal = $datatahun_awal . '-' . sprintf('%02d', $databulan_awal);
        $periode_akhir = $datatahun_akhir . '-' . sprintf('%02d', $databulan_akhir);

        $datatab1 = [];
        $datatab2 = [];

        if ($emptelgroup_id == '01') {
            $dataperiode = date('F', mktime(0, 0, 0, $databulan_awal, 10)) . '/' . $datatahun_awal;

            $api = KaryawanPayslipTelkom::find()
                ->andWhere('employee_id = :param1', [':param1' => $nik])
                ->andWhere('periode_year >= :param2 AND periode_year <= :param3 AND periode_month >= :param4 AND periode_month <= :param5', [':param2' => $datatahun_awal, ':param3' => $datatahun_akhir, ':param4' => sprintf('%02d', $databulan_awal), ':param5' => sprintf('%02d', $databulan_akhir)])
                ->all();
            for ($i = $databulan_awal; $i <= $databulan_akhir; $i++) {
                $dataperiode = date('F', mktime(0, 0, 0, $i, 10)) . '/' . $datatahun_awal;
                if (!empty($api)) {
                    foreach ($api as $adx => $arow) {
                        $dataperiodenotnull = date('F', mktime(0, 0, 0, $arow->periode_month, 10)) . '/' . $arow->periode_year;
                        if ($dataperiode == $dataperiodenotnull) {
                            $datatab1[$i][$dataperiodenotnull][$arow->paytype_name]['person_id'] = $arow->person_id;
                            $datatab1[$i][$dataperiodenotnull][$arow->paytype_name]['periode_year'] = $arow->periode_year;
                            $datatab1[$i][$dataperiodenotnull][$arow->paytype_name]['periode_month'] = $arow->periode_month;
                            $datatab1[$i][$dataperiodenotnull][$arow->paytype_name]['url_scan_dokumen'] = $arow->url_scan_dokumen;
                        }
                    }
                }
                $datatab2[$i][$dataperiode] = [];
            }
        } else {
            $api = Api::listpayslip($nik, $periode_awal, $periode_akhir);
            for ($i = $databulan_awal; $i <= $databulan_akhir; $i++) {
                $dataperiode = date('F', mktime(0, 0, 0, $i, 10)) . '/' . $datatahun_awal;
                if (!empty($api)) {
                    foreach ($api as $adx => $arow) {
                        if ($dataperiode == $arow['periodeGaji']) {
                            $datatab1[$i][$arow['periodeGaji']] = $arow;
                        }
                    }
                }
                $datatab2[$i][$dataperiode] = [];
            }
        }

        $datatab = ($datatab1 + $datatab2);
		// echo '<pre>';
		// var_dump($datatab);exit;
		ksort($datatab);
        return $datatab;
    }

    public function getUriPdf($url)
    {
        $dev = YII_ENV_DEV;
        if ($url != '') {
            $exp = explode('/', $url);
            $revexp = array_reverse($exp);

            if ($dev == true) {
                $filename = $revexp[0];
                $foldername = $revexp[1];
                $nikname = $revexp[2];
                $folderstorage = $revexp[3];
                $name_app = url::base();
                $url = $name_app . '/' . $folderstorage . '/' . $nikname . '/' . $foldername . '/' . $filename;
            } else {
                $filename = $revexp[0];
                $foldername = $revexp[1];
                $nikname = $revexp[2];
                $folderstorage = $revexp[3];
                if ($folderstorage != 'storage') {
                    $url = '/' . $nikname . '/' . $foldername . '/' . $filename;
                } else {
                    $url = '/' . $folderstorage . '/' . $nikname . '/' . $foldername . '/' . $filename;
                }
            }
        }

        return $url;
    }

    public function getUriPenandatangan($url)
    {
        $dev = YII_ENV_DEV;
        if ($url != '') {
            $exp = explode('/', $url);
            $revexp = array_reverse($exp);

            if ($dev == true) {
                $filename = $revexp[0];
                $foldername = $revexp[1];
                $nikname = $revexp[2];
                $folderstorage = $revexp[3];
                $name_app = url::base();
                $url = $name_app . '/' . $folderstorage . '/' . $nikname . '/' . $foldername . '/' . $filename;
            } else {
                $filename = $revexp[0];
                $foldername = $revexp[1];
                $nikname = $revexp[2];
                $folderstorage = $revexp[3];
                if ($folderstorage != 'storage') {
                    $url = '/' . $nikname . '/' . $foldername . '/' . $filename;
                } else {
                    $url = '/' . $folderstorage . '/' . $nikname . '/' . $foldername . '/' . $filename;
                }
            }
        }

        return $url;
    }

    function mapnot($n, $m)
    {
        return [$n['n_tahun'] => $m['n_tahun']];
    }

    public function getTahunSpt()
    {
        $model = Spt::find()->select('n_tahun')->groupBy(['n_tahun'])->orderBy(['n_tahun' => SORT_ASC])->all();
        $data1 = [];
        if (!empty($model)) {
            foreach ($model as $mdx => $mrow) {
                $data1[$mrow->n_tahun] = $mrow->n_tahun;
            }
        }
		
		$spt = SptDokumen::find()->select('periode_year')->groupBy(['periode_year'])->orderBy(['periode_year' => SORT_ASC])->all();
        $data2 = [];
        if (!empty($spt)) {
            foreach ($spt as $mdx => $mrow) {
                $data2[$mrow->periode_year] = $mrow->periode_year;
            }
        }
		
		$data = array_unique(array_merge($data1, $data2));

        return $data;
    }
	
	public function bulanPayslip() {
        $i = 1;
        for ($i = 1; $i <= 12; $i++) {
            $databulan[$i] = date('F', mktime(0, 0, 0, $i, 10));
        }

        return $databulan;
    }

    public function insertAttributesRecognition($model, $person_id, $type)
    {
        $karyawan = KaryawanAllColumn::find()->where(['person_id' => $person_id])->one();
        if (!empty($karyawan)) {
            if ($type == 'SENDER') {
                $model->sender_person_id = $karyawan->person_id;
                $model->sender_employee_id = $karyawan->employee_id;
                $model->sender_person_name = $karyawan->person_name;
                $model->sender_object_id = $karyawan->object_id;
                $model->sender_object_abbr = $karyawan->object_abbr;
                $model->sender_object_name = $karyawan->object_name;
                $model->sender_object_parent = $karyawan->object_parent;
                $model->sender_is_chief = $karyawan->is_chief;
                $model->sender_objectunit_id = $karyawan->objectunit_id;
                $model->sender_objectunit_name = $karyawan->objectunit_name;
                $model->sender_psa_id = $karyawan->psa_id;
                $model->sender_psa_name = $karyawan->psa_name;
                $model->sender_regional_id = $karyawan->regional_id;
                $model->sender_regional_name = $karyawan->regional_name;
                $model->sender_band_id = $karyawan->band_id;
                $model->sender_band_name = $karyawan->band_name;
                $model->sender_job_id = $karyawan->job_id;
                $model->sender_job_name = $karyawan->job_name;
                $model->sender_jobfunction_id = $karyawan->jobfunction_id;
                $model->sender_jobfunction_name = $karyawan->jobfunction_name;
            } else {
                $model->receiver_person_id = $karyawan->person_id;
                $model->receiver_employee_id = $karyawan->employee_id;
                $model->receiver_person_name = $karyawan->person_name;
                $model->receiver_object_id = $karyawan->object_id;
                $model->receiver_object_abbr = $karyawan->object_abbr;
                $model->receiver_object_name = $karyawan->object_name;
                $model->receiver_object_parent = $karyawan->object_parent;
                $model->receiver_is_chief = $karyawan->is_chief;
                $model->receiver_objectunit_id = $karyawan->objectunit_id;
                $model->receiver_objectunit_name = $karyawan->objectunit_name;
                $model->receiver_psa_id = $karyawan->psa_id;
                $model->receiver_psa_name = $karyawan->psa_name;
                $model->receiver_regional_id = $karyawan->regional_id;
                $model->receiver_regional_name = $karyawan->regional_name;
                $model->receiver_band_id = $karyawan->band_id;
                $model->receiver_band_name = $karyawan->band_name;
                $model->receiver_job_id = $karyawan->job_id;
                $model->receiver_job_name = $karyawan->job_name;
                $model->receiver_jobfunction_id = $karyawan->jobfunction_id;
                $model->receiver_jobfunction_name = $karyawan->jobfunction_name;
            }

            return $model;
        } else {
            return $model;
        }
    }

    public function insertAttributesRecognitionKeterangan($model, $type)
    {
        if ($model->sender_type == 'SDM' && $type == 'SENDER') {
            $result = 'Admin SDM memberikan ' . $model->point . ' poin kepada ' . $model->receiver_person_name . '';
        } else if ($model->sender_type == 'KARYAWAN' && $type == 'SENDER') {
            $result = '' . $model->sender_person_name . ' memberikan ' . $model->point . ' poin kepada ' . $model->receiver_person_name . '';
        } else if ($model->sender_type == 'SDM' && $type == 'RECEIVER') {
            $result = 'Anda menerima ' . $model->point . ' poin dari Admin SDM';
        } else {
            $result = 'Anda menerima ' . $model->point . ' poin dari ' . $model->sender_person_name . '';
        }

        return $result;
    }

    public static function monitoringSheetRpHistory($jenis, $model, $dataarr)
    {
        if ($jenis == 'header') {
            $data = [
                'No',
                $model->getAttributeLabel('year_of_event'),
                $model->getAttributeLabel('event_name'),
                $model->getAttributeLabel('point'),
                'Date',
                $model->getAttributeLabel('receiver_employee_id'),
                $model->getAttributeLabel('receiver_person_name'),
                $model->getAttributeLabel('receiver_object_name'),
                $model->getAttributeLabel('receiver_objectunit_name'),
                $model->getAttributeLabel('receiver_band_name'),
                $model->getAttributeLabel('sender_type'),
                $model->getAttributeLabel('sender_employee_id'),
                $model->getAttributeLabel('sender_person_name'),
                $model->getAttributeLabel('sender_object_name'),
                $model->getAttributeLabel('sender_objectunit_name'),
                $model->getAttributeLabel('sender_band_name'),
                $model->getAttributeLabel('sender_reason')
            ];
        } else {
            $dataall = MonitoringRecognitionPoint::find()
                ->andWhere($dataarr['sender_type'], $dataarr['param_sender_type'])
                ->andWhere(LTRIM($dataarr['search_single'], ' AND '), $dataarr['param_single'])
                ->andWhere($dataarr['imp_employee'], $dataarr['params_employee'])
                ->all();

            if (!empty($dataall)) {
                $i = 1;
                foreach ($dataall as $index => $value) {
                    $data[$index][] = $i;
                    $data[$index][] = $value->evtrecog_name;
                    $data[$index][] = $value->year_of_event;
                    $data[$index][] = $value->point;
                    $data[$index][] = Logic::getIndoDate(date('Y-m-d', strtotime($value->created_time))) . ' ' . date('H:i:s', strtotime($value->created_time));
                    $data[$index][] = $value->receiver_employee_id;
                    $data[$index][] = $value->receiver_person_name;
                    $data[$index][] = $value->receiver_object_name;
                    $data[$index][] = $value->receiver_objectunit_name;
                    $data[$index][] = $value->receiver_band_name;
                    $data[$index][] = $value->sender_type;
                    $data[$index][] = $value->sender_employee_id;
                    $data[$index][] = $value->sender_person_name;
                    $data[$index][] = $value->sender_object_name;
                    $data[$index][] = $value->sender_objectunit_name;
                    $data[$index][] = $value->sender_band_name;
                    $data[$index][] = $value->sender_reason;

                    $i++;
                }
            }
        }

        return $data;
    }

    public static function monitoringSheetRpSummary($jenis, $model, $dataarr)
    {
        if ($jenis == 'header') {
            $data = [
                'No',
                $model->getAttributeLabel('evtrecog_name'),
                $model->getAttributeLabel('receiver_employee_id'),
                $model->getAttributeLabel('receiver_person_name'),
                $model->getAttributeLabel('total_point_received_all') . " (F + G)",
                $model->getAttributeLabel('total_point_from_sdm'),
                $model->getAttributeLabel('total_point_from_karyawan'),
                $model->getAttributeLabel('total_point_to_karyawan'),
                $model->getAttributeLabel('balance_point') . " (F + H)",
                $model->getAttributeLabel('convertion_point'),
                $model->getAttributeLabel('point_from_karyawan_to_rupiah') . " (G * J)",
				'Redeem Status',

            ];
        } else {
            $dataall = SummaryRecognitionPoint::find()
                ->andWhere(LTRIM($dataarr['search_single'], ' AND '), $dataarr['param_single'])
                ->andWhere($dataarr['imp_employee'], $dataarr['params_employee'])
                ->all();

            if (!empty($dataall)) {
                $i = 1;
                foreach ($dataall as $index => $value) {
                    $data[$index][] = $i;
                    $data[$index][] = $value->evtrecog_name;
                    $data[$index][] = $value->receiver_employee_id;
                    $data[$index][] = $value->receiver_person_name;
                    $data[$index][] = NUMBER_FORMAT($value->total_point_received_all, 0, ',', '.');
                    $data[$index][] = $value->total_point_from_sdm;
                    $data[$index][] = $value->total_point_from_karyawan;
                    $data[$index][] = $value->total_point_to_karyawan;
                    $data[$index][] = $value->balance_point;
                    $data[$index][] = $value->convertion_point == '' ? 'Belum ditetapkan' : NUMBER_FORMAT($value->convertion_point, 0, ',', '.');
                    $data[$index][] = $value->convertion_point == '' ? 'Belum ditetapkan' : 'Rp. ' . NUMBER_FORMAT($value->point_from_karyawan_to_rupiah, 2);
                    $data[$index][] = !empty($value->app_status) ? $value->app_status : 'NOT YET';

                    $i++;
                }
            }
        }

        return $data;
    }

    public static function getEventRecognitionPointActive()
    {
        $model = EventRecognition::find()->where('start_date <= :p1 and end_date >= :p1', [':p1' => date('Y-m-d')])->one();
        if (!empty($model)) {
            return $model;
        }
        return null;
    }

    /*
     * summary point untuk mendapatkan point dirisendiri, dari karyawann lain dan balance
     * return as model
     */
    public static function getPointKaryawan($person_id)
    {
        $event = self::getEventRecognitionPointActive();
        $model = MvSummaryRecognitionPoint::find()->where(['evtrecog_id' => $event->evtrecog_id, 'receiver_person_id' => $person_id])->one();
        if (!empty($model)) {
            return $model;
        }
        return null;
    }
	
	public function getFile($url)
    {
		if(!empty($url)){
			return url::base().$url;
		}else{
			return url::base() . '/storage/nophoto.png';
		}
    }
	
	public function slugify($string){
		$slugify = new Slugify();
		return $slugify->slugify($string);
	}
}