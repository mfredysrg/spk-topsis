<?php

$metode = $_POST['metode_pembobotan'] ?? '';

if($metode==''){

echo '
<div class="alert alert-warning">
Silakan pilih metode terlebih dahulu.
</div>
';
exit;
}

/* ==========================================
FUNCTION TABEL
========================================== */

function TabelANP($judul,$header,$data){

echo '
<div class="card mb-3">

<div class="card-header bg-primary text-white">
'.$judul.'
</div>

<div class="card-body">

<div class="table-responsive">

<table class="table table-bordered table-sm text-center">

<tr>';

foreach($header as $h){
echo '<th>'.$h.'</th>';
}

echo '</tr>';

foreach($data as $row){

echo '<tr>';

foreach($row as $col){
echo '<td>'.$col.'</td>';
}

echo '</tr>';

}

echo '

</table>

</div>

</div>

</div>

';

}

/* ==========================================
SWARA
========================================== */

if($metode=='SWARA'){

$swara = [

[
'kode'=>'C4',
'nama'=>'Tanggungan Keluarga',
'sj'=>0.00,
'kj'=>1.00,
'qj'=>1.000,
'wj'=>0.2278
],

[
'kode'=>'C5',
'nama'=>'Kepemilikan SKTM',
'sj'=>0.10,
'kj'=>1.10,
'qj'=>0.909,
'wj'=>0.2071
],

[
'kode'=>'C1',
'nama'=>'Omzet Bulanan',
'sj'=>0.15,
'kj'=>1.15,
'qj'=>0.791,
'wj'=>0.1801
],

[
'kode'=>'C6',
'nama'=>'Riwayat Bantuan',
'sj'=>0.20,
'kj'=>1.20,
'qj'=>0.659,
'wj'=>0.1501
],

[
'kode'=>'C2',
'nama'=>'Total Aset Usaha',
'sj'=>0.15,
'kj'=>1.15,
'qj'=>0.573,
'wj'=>0.1305
],

[
'kode'=>'C3',
'nama'=>'Lama Usaha Berdiri',
'sj'=>0.25,
'kj'=>1.25,
'qj'=>0.458,
'wj'=>0.1044
]

];

echo '<h4 class="mb-3">Visualisasi Metode SWARA</h4>';

/* ==========================================
1. SJ
========================================== */

echo '
<div class="card mb-3">
<div class="card-header bg-success text-white">
1. Penentuan Nilai Selisih Kepentingan (Sj)
</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>No</th>
<th>Kode</th>
<th>Kriteria</th>
<th>Sj</th>
</tr>';

$no=1;

foreach($swara as $row){

echo '
<tr>
<td>'.$no++.'</td>
<td>'.$row['kode'].'</td>
<td>'.$row['nama'].'</td>
<td>'.$row['sj'].'</td>
</tr>';
}

echo '
</table>
</div>
</div>';

/* ==========================================
2. KJ
========================================== */

echo '
<div class="card mb-3">
<div class="card-header bg-success text-white">
2. Perhitungan Nilai Koefisien (Kj)
</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>No</th>
<th>Kode</th>
<th>Kriteria</th>
<th>Sj</th>
<th>Kj</th>
</tr>';

$no=1;

foreach($swara as $row){

echo '
<tr>
<td>'.$no++.'</td>
<td>'.$row['kode'].'</td>
<td>'.$row['nama'].'</td>
<td>'.$row['sj'].'</td>
<td>'.$row['kj'].'</td>
</tr>';
}

echo '
</table>
</div>
</div>';

/* ==========================================
3. QJ
========================================== */

echo '
<div class="card mb-3">
<div class="card-header bg-success text-white">
3. Perhitungan Bobot Awal (Qj)
</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>No</th>
<th>Kode</th>
<th>Kriteria</th>
<th>Kj</th>
<th>Qj</th>
</tr>';

$no=1;
$totalQ=0;

foreach($swara as $row){

$totalQ += $row['qj'];

echo '
<tr>
<td>'.$no++.'</td>
<td>'.$row['kode'].'</td>
<td>'.$row['nama'].'</td>
<td>'.$row['kj'].'</td>
<td>'.$row['qj'].'</td>
</tr>';
}

echo '
<tr class="table-info">
<td colspan="4"><b>Total Qj</b></td>
<td><b>'.number_format($totalQ,3).'</b></td>
</tr>

</table>
</div>
</div>';

/* ==========================================
4. WJ
========================================== */

echo '
<div class="card mb-3">
<div class="card-header bg-success text-white">
4. Normalisasi Bobot Final (Wj)
</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>No</th>
<th>Kode</th>
<th>Kriteria</th>
<th>Qj</th>
<th>Wj</th>
<th>Persentase</th>
</tr>';

$no=1;

foreach($swara as $row){

echo '
<tr>
<td>'.$no++.'</td>
<td>'.$row['kode'].'</td>
<td>'.$row['nama'].'</td>
<td>'.$row['qj'].'</td>
<td>'.$row['wj'].'</td>
<td>'.number_format($row['wj']*100,2).'%</td>
</tr>';
}

echo '
</table>
</div>
</div>';

}

/* ==========================================
ANP
========================================== */

if($metode=='ANP'){

echo '<h4 class="mb-3">Visualisasi Metode ANP</h4>';

/* ==========================================
1. MATRIKS C1
========================================== */

$header=['Kriteria','C3','C6'];

$data=[

['C3',1,3],
['C6',0.333333333,1],
['Total',1.333333333,4]

];

TabelANP(
'1. Matriks Pengaruh Terhadap C1 (Omzet)',
$header,
$data
);

/* ==========================================
2. MATRIKS C2
========================================== */

$header=['Kriteria','C1','C6'];

$data=[

['C1',1,3],
['C6',0.333333333,1],
['Total',1.333333333,4]

];

TabelANP(
'2. Matriks Pengaruh Terhadap C2 (Aset)',
$header,
$data
);

/* ==========================================
3. MATRIKS C5
========================================== */

$header=['Kriteria','C1','C2','C4'];

$data=[

['C1',1,3,0.333333333],
['C2',0.333333333,1,0.2],
['C4',3,5,1],
['Total',4.333333333,9,1.533333333]

];

TabelANP(
'3. Matriks Pengaruh Terhadap C5 (SKTM)',
$header,
$data
);

/* ==========================================
4. UNWEIGHTED SUPERMATRIX
========================================== */

$header=['Kriteria','C1','C2','C3','C4','C5','C6'];

$data=[

['C1',0,0,0.75,0.166666667,0.166666667,0.260497956],
['C2',0,0,0,0.166666667,0.166666667,0.106156324],
['C3',0.75,0,0,0.166666667,0.166666667,0],
['C4',0,0,0,0.166666667,0.166666667,0.63334572],
['C5',0,0,0,0.166666667,0.166666667,0],
['C6',0.25,0.25,0,0.166666667,0.166666667,0]

];

TabelANP(
'4. Unweighted Supermatrix',
$header,
$data
);

/* ==========================================
5. WEIGHTED SUPERMATRIX
========================================== */

TabelANP(
'5. Weighted Supermatrix',
$header,
$data
);

/* ==========================================
6. ITERASI W²
========================================== */

$data=[

['C1 (Omzet)',0.125,0,0.223971882,0.223971882,0.185174863,0.260497956],
['C2 (Aset)',0.125,0,0.073248276,0.073248276,0.10555762,0.106156324],
['C3 (Lama)',0.125,0.5625,0.180555556,0.180555556,0.300931087,0],
['C4 (Tanggungan)',0.125,0,0.161113176,0.161113176,0.10555762,0.63334572],
['C5 (SKTM)',0.375,0.25,0.222222222,0.222222222,0.10555762,0],
['C6 (Bantuan)',0.125,0.1875,0.138888889,0.138888889,0.19722119,0]

];

TabelANP(
'6. Iterasi 1 (W²)',
$header,
$data
);

/* ==========================================
7. ITERASI W⁴
========================================== */

$data=[

['C1 (Omzet)',0.173620788,0.221121266,0.181850915,0.181850915,0.185111233,0.174413877],
['C2 (Aset)',0.086790717,0.087495871,0.091224313,0.091224313,0.085000143,0.078953727],
['C3 (Lama)',0.243925547,0.176795272,0.197762403,0.197762403,0.187682383,0.206629265],
['C4 (Tanggungan)',0.174655616,0.235767889,0.194465752,0.194465752,0.224689152,0.134602585],
['C5 (SKTM)',0.173264663,0.151389405,0.201685158,0.201685158,0.197303214,0.264969308],
['C6 (Bantuan)',0.147742668,0.127430297,0.133011459,0.133011459,0.120213876,0.140431239]

];

TabelANP(
'7. Iterasi 2 (W⁴)',
$header,
$data
);

/* ==========================================
8. ITERASI W⁸
========================================== */

$data=[

['C1 (Omzet)',0.183296426,0.183012926,0.183605016,0.183605016,0.183414648,0.183335499],
['C2 (Aset)',0.087239637,0.08741181,0.087190484,0.087190484,0.087383543,0.08678427],
['C3 (Lama)',0.203521035,0.205739417,0.203390905,0.203390905,0.20360261,0.202732751],
['C4 (Tanggungan)',0.191003246,0.190646197,0.192764144,0.192764144,0.193076151,0.193873162],
['C5 (SKTM)',0.200976098,0.19840105,0.199462413,0.199462413,0.19889212,0.200483183],
['C6 (Bantuan)',0.133963559,0.1347886,0.133587038,0.133587038,0.133630928,0.132791135]

];

TabelANP(
'8. Iterasi 3 (W⁸)',
$header,
$data
);

/* ==========================================
9. ITERASI W¹⁶
========================================== */

$data=[

['C1 (Omzet)',0.183422434,0.183422687,0.183422758,0.183422758,0.183422799,0.183423101],
['C2 (Aset)',0.087203184,0.087202376,0.087203049,0.087203049,0.087202955,0.087203466],
['C3 (Lama)',0.203574019,0.203573299,0.203573871,0.203573871,0.20357415,0.203573622],
['C4 (Tanggungan)',0.192467883,0.192468129,0.192466554,0.192466554,0.192466351,0.192467324],
['C5 (SKTM)',0.199669404,0.199671103,0.199670402,0.199670402,0.199670279,0.199669031],
['C6 (Bantuan)',0.133663076,0.133662406,0.133663366,0.133663366,0.133663466,0.133663455]

];

TabelANP(
'9. Iterasi 4 (W¹⁶)',
$header,
$data
);

/* ==========================================
10. LIMIT SUPERMATRIX
========================================== */

echo '

<div class="card">

<div class="card-header bg-success text-white">
10. Limit Supermatrix (Bobot Global)
</div>

<div class="card-body">

<table class="table table-bordered">

<tr>
<th>Kriteria</th>
<th>Bobot Global</th>
<th>Persentase</th>
</tr>

<tr><td>C1 (Omzet)</td><td>0.183422434</td><td>18.34%</td></tr>
<tr><td>C2 (Aset)</td><td>0.087203184</td><td>8.72%</td></tr>
<tr><td>C3 (Lama)</td><td>0.203574019</td><td>20.36%</td></tr>
<tr><td>C4 (Tanggungan)</td><td>0.192467883</td><td>19.25%</td></tr>
<tr><td>C5 (SKTM)</td><td>0.199669404</td><td>19.97%</td></tr>
<tr><td>C6 (Bantuan)</td><td>0.133663076</td><td>13.37%</td></tr>

<tr class="table-info">
<td><b>Total Bobot Final</b></td>
<td><b>1</b></td>
<td><b>100%</b></td>
</tr>

</table>

</div>

</div>

';

}
?>