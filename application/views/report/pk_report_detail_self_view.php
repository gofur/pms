<div class="report">
	<div class="row">
		<br>
		<div class="span12">
			<div class="btn-group pull-right">
						<button type="submit" class="btn" id="id_button_print" onclick="printdiv('printable_area')" name="btn_submit_print" value="Print">print</button>
			</div>
		</div>
	</div>
<div id="printable_area">
	<div class="row" >
		<div class="span12 text-center">
			<center><h4>FORMULIR PENILAIAN KINERJA</h4></center>
			<center><h6>PT KOMPAS GRAMEDIA</h6></center>
			<center><small>VISI & MISI : MENJADI AGEN PERUBAHAN DALAM MEMBANGUN KOMUNITAS INDONESIA YANG LEBIH HARMONIS, TOLERAN, AMAN & SEJAHTERA 
	DENGAN MEMPERTAHANKAN KOMPAS SEBAGAI MARKET LEADER SECARA NASIONAL MELALUI OPTIMALISASI SUMBERDAYA SERTA SINERGI BERSAMA MITRA STRATEGI</small></center>
		</div>
	</div>
	<br>
	
	<div class="row">
		<div class="span6 offset1 kotak_report">
			<div class="row">
				<div class="span2">NIK :</div>
				<div class="span4"><strong><?=$NIK?></strong></div>
			</div>
			<div class="row">
				<div class="span2">Name :</div>
				<div class="span4"><strong><?php echo $data_individu->Fullname ?></strong></div>
			</div>
			<!-- <div class="row">
				<div class="span2">Jabatan :</div>
				<div class="span4"><strong><?php echo $data_individu->PositionName ?></strong></div>
			</div> -->
			<!-- <div class="row">
				<div class="span2">Unit Kerja :</div>
				<div class="span4"><strong><?php echo $org_name ?></strong></div>
			</div> -->
			<div class="row">
				<div class="span2">Periode PK :</div>
				<div class="span4"><strong><?php echo $periode ?></strong></div>
			</div>
			<!-- <div class="row">
				<div class="span2">Atasan :</div>
				<div class="span4"><strong><?php echo $chief_nik.' - '.$nama_chief ?></strong></div>
			</div> -->
		</div>
	</div>
	<div class="row">
		<div class="span12">
			<center>PETUNJUK PENILAIAN</center>
		</div>
	</div>


	<div class="row">
		<div class="span12">
			<strong>A.   	ASPEK HASIL KERJA (BOBOT 70%)</strong>
			<br>
		</div>
		<div class="span12">
			<ol>
  				<li>Rencana Kinerja Individu (RKI) yang telah dibuat pada awal periode dipindahkan pada kolom-kolom sebagai berikut: kolom 1 (Objective), kolom 2 (KPI), kolom 3 (Target), dan kolom 4 (Bobot).</li>
  				<li>Pencapaian atau realisasi dari RKI akan dinilai sebagai Aspek Hasil Kerja pada akhir periode PK I (Juni) dan periode PK II (Desember).</li>
  				<li>Cara pengisian Penilaian Kinerja (PK) adalah dengan cara membandingkan antara Target dalam RKI dengan Pencapaian Target. Berdasarkan skala nilai 1 s.d 5 sesuai dengan kategori penilaian pada tabel "Petunjuk Penilaian KPI"</li>
  				<li>Penilaian dilakukan secara berjenjang : 
  					<ol>
  						<li>karyawan menilai dirinya sendiri ((kolom 6) dengan memberikan bukti2 pencapaian target (kolom 6),</li>
  						<li>Penilaian atasan terhadap kinerja karyawan (kolom 7) </li>
  						<li>Nilai final (kolom 8) merupakan hasil kesepakatan antara karyawan & atasan. Sub total nilai  Aspek Hasil Kerja merupakan hasil pengalian antara Bobot & Nilai Final (kolom 4 x kolom 8)</li>
  					</ol></li>
  				<li>Untuk memperkuat penilaian, karyawan dapat melengkapi dengan catatan-catatan atau dokumen pendukung pada kolom yang telah disediakan</li>
  				<li>Untuk KPI yang bersifat kualitatif, karyawan dapat menggunakan Petunjuk Penilaian Kualitatif dalam bentuk skala yang telah disedikan oleh unitnya. Contoh : Skala penilaian kualitas tulisan (Unit Redaksi), 
     			Skala penilaian kualitas sekretariat (untuk jabatan Sekretaris/Sekretariat). Jika unit belum menyediakan, silahkan menggunakan petunjuk penilaian KPI kualitatif yang tersedia di bawah kolom Aspek Hasil Kerja. </li>
			</ol>
		</div>

		<div class="span12">
			<strong>B.   	ASPEK SIKAP KERJA (BOBOT 30%)</strong>
			<br>
		</div>
		<div class="span12">
			<ol class="unstyled">
  				<li> Penilaian aspek ini dilakukan dengan memperhatikan usaha karyawan dalam melaksanakan nilai-nilai Perusahaan yang terangkum dalam 5C (Caring, Credible, Competent, Competitive Customer Delight), 
 pada masing-masing kolom penilaian skala 1 s.d 5. </li>
			</ol>
		</div>

		<div class="span12">
			<strong>C.	PENUGASAN PADA PROYEK (Project Assigment)</strong>
			<br>
		</div>
		<div class="span12">
			<ol class="unstyled">
  				<li>  Yang dimaksud dengan Proyek adalah proyek dalam lingkup korporat maupun unit bisnis yang ditetapkan dalam surat keputusan/surat penugasan tersendiri dalam batasan waktu tertentu. Proyek ini  merupakan tambahan 
						 dan tidak mengurangi kewajiban karyawan untuk memenuhi target pada RKI yang telah disepakati di awal periode. Proyek khusus ini  dan dinilai secara terpisah dari Aspek Hasil Kerja. Maksimal penilaian untuk seluruh
 						proyek adalah 0,6 dan langsung ditambahkan pada Nilai Akhir PK. 
				</li>
			</ol>
		</div>

		<div class="span12">
			<strong>D.	NILAI AKHIR</strong>
			<br>
		</div>
		<div class="span12">
			<ol class="unstyled">
  				<li>   Nilai akhir adalah sub total nilai Aspek Hasil Kerja + sub total nilai Aspek Sikap Kerja + Nilai Project Assignment. Nilai akhir ini akan dikonversi menjadi Kategori Penilaian (Istimewa / Memuaskan / Bagus / Kurang / Kurang Sekali)

				</li>
			</ol>
		</div>

		<div class="span12">
			<strong>E.	TANGGAPAN - TANGGAPAN</strong>
			<br>
		</div>
		<div class="span12">
			<ol class="unstyled">
  				<li>    Kolom tanggapan yang disediakan harap diisi sesuai kebutuhan pengembangan karyawan dan sesuai kebutuhan unit. </li>
			</ol>
		</div>
	</div>

	<div class="row">
		<div class="span12">
			<strong>A. ASPEK HASIL KERJA (BOBOT 70%)</strong>
			<br>
		</div>
		<div class="span12">
			<?php
				// $this->load->library('table');
				$tmpl = array (
              'table_open'          => '<table class="table table-bordered table-stripted table-hover">',
              'table_close'         => '</table>'
        );
        // $cell = array('data' => 'Total', 'colspan' => 4);
				foreach ($post_ls as $post) {
					echo '<dl class="dl-horizontal">';
					echo '<dt>Position Name</dt>';
					echo '<dd>'.$post->PositionName.'</dd>';

					echo '<dt>Start - End</dt>';
					echo '<dd>'.date('d M Y', strtotime($rkk_begin[$post->PositionID])). ' - '. date('d M Y', strtotime($rkk_end[$post->PositionID])).'</dd>';

					echo '<dt>Achv Position</dt>';
					echo '<dd>'.$post_achv[$post->PositionID].'</dd>';
					echo '</dl>';
					$this->table->set_template($tmpl);
					$this->table->set_heading(array('Objective','KPI', 'Target', 'Achievement','Weight','PC','Weight x PC'));
					foreach ($kpi_list[$post->PositionID] as $kpi) {
						$this->table->add_row(
							$kpi->SasaranStrategis, 
							$kpi->KPI, 
							round($kpi_target[$kpi->KPIID],2),
							round($kpi_achv[$kpi->KPIID],2),
							round($kpi->Bobot,2),
							round($pc[$kpi->KPIID],2),
							round($nxb[$kpi->KPIID],2)
						);
					}
					$this->table->add_row(
						'Total',
						'',
						'',
						'',
						$total_bobot[$post->PositionID],
						'',
						$total_nxb[$post->PositionID]
					);
					

					echo $this->table->generate();
					echo '<hr>';
					$this->table->clear();
				}
			?>


		</div>
	</div>

	<div class="row">
		<div class="span6">
			<table class="table table-bordered table-stripted table-hover">
			<thead>
				<tr><th colspan='2'><center>PENUNJUK PENILAILAN KPI</center></th></tr>
				<tr><th>KUALITATIF</th><th>KUANTITATIF</th><th>NILAI</th></tr>
			</thead>
			<tbody>
				<tr>
					<td>Tidak dapat diterima (Unacceptable)</td>
					<td>< = 70%</td>
					<td>1</td>
				</tr>
				<tr>
					<td>Butuh perbaikan (Need Improvement)</td>
					<td>> 70% - 95%</td>
					<td>2</td>
				</tr>
				<tr>
					<td>Sesuai harapan (Meet Expectation)</td>
					<td>> 95% - 115%</td>
					<td>3</td>
				</tr>
				<tr>
					<td>Melebihi harapan (Exceed Expectation)</td>
					<td>> 115% - 130%</td>
					<td>4</td>
				</tr>
				<tr>
					<td>Istimewa (Outstanding)</td>
					<td>> 130%</td>
					<td>5</td>
				</tr>
			</tbody>
		</table>
		</div>
	</div>
	
	<br>
	
	<div class="row">
		<div class="span12">
			<strong>B. ASPEK SIKAP KERJA (BOBOT 30%)</strong>
			<br>
		</div>
		<div class="span12">
			<table class="table table-bordered table-stripted table-hover">
			<thead>
				<tr>
					<th rowspan="2">NILAI-NILAI PERUSAHAAN YANG HARUS DIMILIKI KARYAWAN
						<!--<th colspan="5"> PETUNJUK PENILAIAN (SKALA) -->
    					<th rowspan="2">BOBOT</th>
    					<th rowspan="2">NILAI FINAL</th>
    					<th rowspan="2">N X B</th>
    			</tr>
    			<!--<tr>
  					<th>1</th>
  					<th>2</th>
  					<th>3</th>
  					<th>4</th>
  					<th>5</th>
				</tr>-->
			</thead>
	<tbody>		
<?php
if(isset($aspect_setting)!='')
{
	$i=0;
	$sum_achieve=0;
	
	foreach ($aspect_setting as $key) 
	{
		
		if(isset($detail_aspect_setting)==1)
		{

			foreach ($detail_aspect_setting[$key->aspect_setting_id] as $row) 
			{

				foreach ($data_behaviour[$row->behaviour_group_id]  as $row_behaviour) 
				{					
					
?>


			
				<tr>
					<td><?php 	echo $row_behaviour->sort_number.'. '.$row_behaviour->label; ?></td>
					<td><?php 	echo $row_behaviour->weight; ?></td>
					<td><?php 	echo $answer[$i]; ?></td>
					<td><?php echo $total=$row_behaviour->weight/100*$answer[$i] ?></td>
					<!--<td>asdasd</td>
					<td>asdasd</td>
					<td>asdasd</td>
					<td>asdasd</td>
					<td>asdasd</td>-->
				</tr>
			
<?php
			$sum_achieve+=$total;
			$i++;
				}
			}
		}
	}
}
?>
		<tr>
			<td colspan='3'><strong>SUB  TOTAL ASPEK SIKAP KERJA</strong></td>			
			<td><strong><?php echo $sum_achieve ?></strong></td>
		</tr>
		</tbody>
		</table>
		</div>
	</div>

	<br>
	<div class="row">
		<div class="span12">
			<strong>C. PROJECT ASSIGNMENT</strong>
			<br>
		</div>
		<div class="span12">
			<table class="table table-bordered table-stripted table-hover">
			<thead>
				<tr><th  colspan='4'><center>DESKRIPSI PROJECT</center></th></th></tr>
				<tr><th>NAMA PROJECT</th><th>NO SK / SURAT PENUGASAN</th><th>KEY PERFORMANCE INDIKATOR (KPI)</th>
					<th>NILAI DARI PROJECT</th></tr>
			</thead>
			
			<tbody>
				<?php
					if($project_assign!=0)
					{
						foreach ($project_assign as $row_pro) {
							echo '<tr>
									<td>'.$row_pro->project_name.'</td>
									<td>'.$row_pro->doc_num.'</td>
									<td>'.$row_pro->kpi.'</td>
									<td>'.round($row_pro->result,2).'</td>
								</tr>';									
						}		
					}
				?>

			</tbody>
		</table>
		</div>
	</div>
	
	<div class="row">

		<div class="span6">
			<strong>D. NILAI AKHIR</strong>
			
			<table class="table table-bordered table-stripted table-hover">
			<thead>
				<tr><th  colspan='4'><center>NILAI AKHIR</center></th></tr>
			</thead>
			<tbody>
				<?php
					
					if(count($adjustment_data)!=0)
					{
							echo '<tr>
									<td>'.$adjustment_data->nik.'</td>
									<td>'.round($adjustment_data->after_value, 2).'</td>
									<td style="background-color:'.$adjust_color_text->Colour.'" >'.$adjust_color_text->cat_en_short.'</td>
								</tr>';
					}
				?>
				
			</tbody>
		</table>
		</div>

		<div class="span6">
			<br>
			<table class="table table-bordered table-stripted table-hover">
			<thead>
				<tr><th  colspan='1'><center>TOTAL NILAI AKHIR</center></th></th><th  colspan='2'><center>KATEGORI PENILAIAN</center></th></tr>
			</thead>
			
			<tbody>
				<tr>
					<td>4,41 - 5</td>
					<td>Outstanding (Istimewa)</td>
					<td style="background-color:#0066FF">OS</td>
				</tr>
				<tr>
					<td>3,51 -  4,40</td>
					<td>Exceed Expectation (Memuaskan)</td>
					<td style="background-color:#669900">EE</td>
				</tr>
				<tr>
					<td>2,51 -  3,50</td>
					<td>Meet Expectation (Bagus)</td>
					<td style="background-color:#99FF00">ME</td>
				</tr>
				<tr>
					<td>1,61 -  2,50</td>
					<td>Need Improvement (Kurang)</td>
					<td style="background-color:#FFFF00">NI</td>
				</tr>
				<tr>
					<td>< 1,61</td>
					<td>Unacceptable (Kurang Sekali)</td>
					<td style="background-color:#FF3333">UA</td>
				</tr>
			</tbody>
		</table>
		</div>
	</div>
</div>
<!-- 
	<div class="row">
		<div class="span12">
			<strong>F. 1. TANGGAPAN KARYAWAN ATAS HASIL PENILAIAN KARYA</strong>
			<br>
			Tanggapan Karyawan atas Nilai Akhir PK :
		</div>
		<div class="span12">
			<strong>F.2. TANGGAPAN PARA ATASAN</strong>
			<br>
			Rekomendasi Pengembangan :
		</div>
	</div> -->

</div>
<?php 
if(!empty($kpi_list))
{
	?>
<?php echo form_open($process,'id="periodForm"');?>
<div>
	<div class="row">
		<div class="span12">
			<center>
				<br>
				<button type="submit" class="btn btn-primary" id='id_agree' name="btn_agree" value="Agree">Agree</button>
			</center>
		</div>
	</div>
</div>
<?php echo form_close(); } ?>
