<html>

<head>
    <title>Inform Conern</title>
    <style>
    /*.page-break {
        page-break-after: always;
    }*/
    </style>
</head>

<body>
    <div class="page-break"></div>
        <table align="center" border="0" cellpadding="1">
            <tbody>
                <tr>
                    <td colspan="3">
                        <div align="center">
                            <span style="font-family: Verdana; font-size: x-small;"><b>SURAT PERSETUJUAN/PENOLAKAN MEDIS KHUSUS</b></span>
                            <hr />
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table border="0" cellpadding="1" style="width: 100%;">
                            <tbody>
                                <tr>
                                    <td width="93"><span style="font-size: x-small;">Nomor Pasien</span></td>
                                    <td width="8"><span style="font-size: x-small;">:</span></td>
                                    <td width="200"><span style="font-size: x-small;"><?php echo $data['patient']->patient_unique_id; ?></span></td>
                                </tr>
                                <tr>
                                    <td><span style="font-size: x-small;">Nomor Konsultasi</span></td>
                                    <td><span style="font-size: x-small;">:</span></td>
                                    <td><span style="font-size: x-small;">{{ $data['patient']->appointment[0]->appointment_no }}</span></td>
                                </tr>
                                <tr>
                                    <td><span style="font-size: x-small;">Perihal</span></td>
                                    <td><span style="font-size: x-small;">:</span></td>
                                    <td><span style="font-size: x-small;">{{ $data['data']['inform-notes'] }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td valign="top">
                        <div align="right">
                            <span style="font-size: x-small;">{{ trim(setting()->get('city_name')) }}, {{ $data['date'] }}</span></div>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <table border="0" style="width: 170px;">
                            <tbody>
                                <tr>
                                    <td width="160"><span style="font-size: x-small;">Saya yang bertanda tangan di bawah ini :</span></td>
                                    <td width="11">
                                    </td>
                                    <td width="140"></td>
                                </tr>
                                <tr>
                                    <td width="93"><span style="font-size: x-small;">Nama</span></td>
                                    <td>:</td>
                                    <td><span style="font-size: x-small;">{{ $data['patient']->patient_name }}</span></td>
                                </tr>
                                <tr>
                                    <td><span style="font-size: x-small;">Jenis Kelamin(L/P)</span></td>
                                    <td>:</td>
                                    <td><span style="font-size: x-small;">{{ ($data['patient']->gender == 'F' ? 'Perempuan' : 'Laki - laki') }}</span></td>
                                </tr>
                                <tr>
                                    <td><span style="font-size: x-small;">Umur</span></td>
                                    <td>:</td>
                                    <td><span style="font-size: x-small;">{{ $data['patient']->age }}</span></td>
                                </tr>
                                <tr>
                                    <td><span style="font-size: x-small;">Alamat</span></td>
                                    <td>:</td>
                                    <td><span style="font-size: x-small;">{{ $data['patient']->address }}</span></td>
                                </tr>
                                <tr>
                                    <td><span style="font-size: x-small;">Telp</span></td>
                                    <td>:</td>
                                    <td><span style="font-size: x-small;">{{ $data['patient']->phone_number }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3" height="270" valign="top">
                        <div align="justify">
                            <p><span style="font-size: x-small;">&nbsp;Menyatakan dengan sesungguhnya dari saya sendiri/*sebagai orang tua/*suami/*istri/*anak/*walidari:</span></p>
                            <table border="0" style="width: 170px;">
                                <tbody>
                                    <tr>
                                        <td width="160"><span style="font-size: x-small;">Saya yang bertanda tangan di bawah ini :</span></td>
                                        <td width="11">
                                        </td>
                                        <td width="140"></td>
                                    </tr>
                                    <tr>
                                        <td width="93"><span style="font-size: x-small;">Nama</span></td>
                                        <td>:</td>
                                        <td><span style="font-size: x-small;">{{ $data['data']['inform-name'] }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><span style="font-size: x-small;">Jenis Kelamin(L/P)</span></td>
                                        <td>:</td>
                                        <td><span style="font-size: x-small;">{{ $data['data']['inform-gender'] }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><span style="font-size: x-small;">Umur/Tgl Lahir</span></td>
                                        <td>:</td>
                                        <td><span style="font-size: x-small;">{{ $data['data']['inform-age'] }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><span style="font-size: x-small;">Alamat</span></td>
                                        <td>:</td>
                                        <td><span style="font-size: x-small;">{{ $data['data']['inform-address'] }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><span style="font-size: x-small;">Telp</span></td>
                                        <td>:</td>
                                        <td><span style="font-size: x-small;">{{ $data['data']['inform-phone'] }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div align="justify">
                                <p><span style="font-size: x-small;">Dengan ini menyatakan SETUJU/MENOLAK untuk dilakukan Tindakan Medis berupa <strong>{{ $data['data']['inform-notes'] }}</strong>.Dari penjelasan yang diberikan, telah saya mengerti segala hal yang berhubungan dengan penyakit tersebut, serta tindakan medis yang akan dilakukan dan kemungkinana pasca tindakan yang dapat terjadi sesuai penjelasan yang diberikan.</span></p>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width="30%">
                        <div align="center">
                            <span style="font-size: x-small;">Dokter/Pelaksana,</span></div>
                        <div align="center">
                            <img src="{{ $data['data']['signature'] }}" width="150px">
                        </div>
                        <div align="center">
                            <span style="font-size: x-small;">{{ $logged_in_user->name }} </span>
                        </div>
                    </td>
                    <td></td>
                    <td valign="top" width="50%">
                        <div align="center">
                            <span style="font-size: x-small;">Yang membuat pernyataan,</span></div>
                        <div align="center">
                            <img src="{{ $data['data']['signature2'] }}" width="150px">
                        </div>
                        <div align="center">
                            <span style="font-size: x-small;">{{ $data['data']['inform-name'] }}</span></div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>