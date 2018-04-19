<?php

    //koneksi
    include "../config/koneksi.php";
    // Load plugin PHPExcel nya
    require_once '../phpexcel/PHPExcel.php';

    // Panggil class PHPExcel nya
    $excel = new PHPExcel();
    // Settingan awal file excel
    $excel->getProperties()->setCreator('Author')
                 ->setLastModifiedBy('Author')
                 ->setTitle("KODIFIKASI PROVINSI")
                 ->setSubject("KODIFIKASI PROVINSI")
                 ->setDescription("KODIFIKASI PROVINSI")
                 ->setKeywords("KODIFIKASI PROVINSI");

     // Buat sebuah variabel untuk menampung pengaturan style dari header tabel
     $style_col = array(
         'font' => array('bold' => true), // Set font nya jadi bold
         'alignment' => array(
             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
         ),
         'borders' => array(
             'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            ),
        ),
     );

     // Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
     $style_row = array(
         'alignment' => array(
             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
         ),
         'borders' => array(
             'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            ),
             'inside' => array(
               'style' => PHPExcel_Style_Border::BORDER_THIN
           ),
         ),
         'font' => array(
             'size' => 9 // Set text jadi di tengah secara vertical (middle)
         )
     );

     $excel->setActiveSheetIndex(0)->setCellValue('A2', "KODIFIKASI PROVINSI");
     $excel->getActiveSheet()->mergeCells('A2:D2');
	 $excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE); // Set bold kolom A1
	 $excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(12); // Set font size 15 untuk kolom A1
     $excel->getActiveSheet()->getStyle('A5:C5')->getFont()->setBold(TRUE);
	 $excel->getActiveSheet()->getStyle('A5:C5')->getFont()->setSize(9);
     $excel->getActiveSheet()->getStyle('A5:C5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom
     $excel->getActiveSheet()->getStyle('A5:C5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER); // Set text center untuk kolom

     // Buat header tabel nya pada baris ke 3
     $excel->setActiveSheetIndex(0)->setCellValue('A5', "No.");
	 $excel->setActiveSheetIndex(0)->setCellValue('B5', "Kode Provinsi");
     $excel->setActiveSheetIndex(0)->setCellValue('C5', "Nama Provinsi");

     // Apply style header yang telah kita buat tadi ke masing-masing kolom header
     $excel->getActiveSheet()->getStyle('A5')->applyFromArray($style_col);
     $excel->getActiveSheet()->getStyle('B5')->applyFromArray($style_col);
     $excel->getActiveSheet()->getStyle('C5')->applyFromArray($style_col);

     // Set height baris ke 1, 2 dan 3
	 $excel->getActiveSheet()->getRowDimension('1')->setRowHeight(16);
	 $excel->getActiveSheet()->getRowDimension('2')->setRowHeight(16);
	 $excel->getActiveSheet()->getRowDimension('3')->setRowHeight(16);
	 $excel->getActiveSheet()->getRowDimension('4')->setRowHeight(16);
	 $excel->getActiveSheet()->getRowDimension('5')->setRowHeight(20);

	 // Set width kolom
	 $excel->getActiveSheet()->getColumnDimension('A')->setWidth(8); // Set width kolom

	 // Freeze Pane
	 $excel->getActiveSheet()->freezePane('B6');

     // Buat query untuk menampilkan semua data siswa
     $no  		= 1;
     $numrow 	= 6;
     $sql 		= mysqli_query($koneksi,("SELECT * FROM tb_prov"));
     while($data = mysqli_fetch_array($sql)){

        $excel->getActiveSheet()->setCellValue('A'.$numrow, $no)
                                ->setCellValue('B'.$numrow, $data['kode_prov'])
                                ->setCellValue('C'.$numrow, $data['nama_prov']);

        // Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
        $excel->getActiveSheet()->getStyle('A'.$numrow.':C'.$numrow)->applyFromArray($style_row);

        // Height
        $excel->getActiveSheet()->getRowDimension($numrow)->setRowHeight(18);

        // Font Size
        $excel->getActiveSheet()->getStyle('A'.$numrow)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // Set text center untuk kolom

        // Auto width
        foreach(range('B'.$numrow,'C'.$numrow) as $columnID) {
            $excel->getActiveSheet()->getColumnDimension($columnID)
            ->setAutoSize(true);
        }

        $no++; // Tambah 1 setiap kali looping
        $numrow++; // Tambah 1 setiap kali looping
     }

     // Set orientasi kertas jadi LANDSCAPE
	 $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
	 // Set judul file excel nya
	 $excel->getActiveSheet(0)->setTitle("KODFIKASI PROVINSI");
	 $excel->setActiveSheetIndex(0);
	 // Proses file excel
	 header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	 header('Content-Disposition: attachment; filename="Kodifikasi Provinsi.xlsx"'); // Set nama file excel nya
	 header('Cache-Control: max-age=0');
	 $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
	 $write->save('php://output');

?>
