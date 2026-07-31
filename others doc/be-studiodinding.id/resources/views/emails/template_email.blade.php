<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Studio Dinding</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin:0; padding:0; background:#f5f7fa;">
  <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5f7fa;">
    <tr>
      <td align="center" style="padding:24px;">
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:600px; background:#ffffff; border-radius:12px; overflow:hidden;">
          <tr>
            <td style="background:#0f172a; padding:24px; text-align:center;">
              <img src="{{ asset('assets/img/sd-logo-1.png') }}" alt="Logo" width="140" height="40" style="display:block; margin:0 auto;">
            </td>
          </tr>
          <tr>
            <td style="padding:24px;">
              <h1 style="margin:0 0 12px; font-family:Arial, sans-serif; font-size:22px; color:#0f172a;">
                Pesan dari Website
              </h1>
              <p style="margin:0 0 16px; font-family:Arial, sans-serif; font-size:15px; line-height:1.6; color:#334155;">
                <table>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $data['name'] }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $data['email'] }}</td>
                    </tr>
                    <tr>
                        <th>Subject</th>
                        <td>{{ $data['subject'] }}</td>
                    </tr>
                    <tr>
                        <th>Pesan</th>
                        <td>{{ $data['message'] }}</td>
                    </tr>
                </table>
              </p>
            </td>
          </tr>
          <tr>
            <td style="background:#f8fafc; padding:20px; text-align:center;">
              <p style="margin:0; font-family:Arial, sans-serif; font-size:12px; color:#94a3b8;">
                <p>Studio Dinding</p>
                <p>Jl. Tanjung Duren Barat IV No.22A, RT.11/RW.6,</p>
                <p>Tj. Duren Utara, Kec. Grogol Petamburan</p>
                <p>Kota Jakarta Barat</p>
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
