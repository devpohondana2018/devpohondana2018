<!DOCTYPE html>
<html>
<head>
</head>
<body>
<h3>Pengajuan Pinjaman #{{$loan->id}}</h3>
<h4>Data Diri</h4>
	<table>
	<tr>
		<td>Nama:</td>
		<td>{{$loan->user->name}}</td>
	</tr>
	<tr>
		<td>Handphone:</td>
		<td>{{$loan->user->mobile_phone}}</td>
	</tr>
	<tr>
		<td>Alamat Email:</td>
		<td>{{$loan->user->email}}</td>
	</tr>
	<tr>
		<td>Alamat:</td>
		<td>{{$loan->user->home_address}}</td>
	</tr>
	<tr>
		<td>Kota:</td>
		<td>{{$loan->user->home_city}}</td>
	</tr>
	<tr>
		<td>Provinsi:</td>
		<td>{{$loan->user->home_state}}</td>
	</tr>
	<tr>
		<td>Kode Pos:</td>
		<td>{{$loan->user->home_postal_code}}</td>
	</tr>
	<tr>
		<td>No. Telepon:</td>
		<td>{{$loan->user->home_phone}}</td>
	</tr>
	<tr>
		<td>No. KTP:</td>
		<td>{{$loan->user->id_no}}</td>
	</tr>
	<tr>
		<td>No. NPWP:</td>
		<td>{{$loan->user->npwp_no}}</td>
	</tr>
	<tr>
		<td>Tempat Lahir:</td>
		<td>{{$loan->user->pob}}</td>
	</tr>
	<tr>
		<td>Tgl Lahir:</td>
		<td>{{$loan->user->dob}}</td>
	</tr>
	<tr>
		<td>Status Tempat Tinggal:</td>
		<td>{{ucfirst($loan->user->home_ownership)}}</td>
	</tr>
</table>
<h4>Pekerjaan</h4>
<table>
	<tr>
		<td>Perusahaan:</td>
		<td>{{$loan->user->company->name}}</td>
	</tr>
	<tr>
		<td>Gaji / Bulan:</td>
		<td>Rp {{number_format($loan->user->employment_salary,2)}}</td>
	</tr>
	<tr>
		<td>Jabatan:</td>
		<td>{{$loan->user->employment_position}}</td>
	</tr>
	<tr>
		<td>Lama Bekerja:</td>
		<td>{{$loan->user->employment_duration}} bulan</td>
	</tr>
	<tr>
		<td>Status Pegawai:</td>
		<td>{{ucfirst($loan->user->employment_status)}}</td>
	</tr>
</table>
<h4>Pinjaman</h4>
<table>
	<tr>
		<td>Jumlah Pinjaman:</td>
		<td>Rp {{number_format($loan->amount_total,2)}}</td>
	</tr>
	<tr>
		<td>Tenor:</td>
		<td>{{$loan->tenor->month}} bulan</td>
	</tr>
	<tr>
		<td>Tujuan Pinjaman:</td>
		<td>{{$loan->description}}</td>
	</tr>
	<tr>
		<td>Tanggal Pengajuan:</td>
		<td>{{$loan->created_at->format('d/m/Y')}}</td>
	</tr>
	<tr>
		<td>Status Pinjaman:</td>
		<td>{{ucfirst($loan->status->name)}}</td>
	</tr>
</table>
</body>
</html>