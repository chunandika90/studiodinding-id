<?php
session_start();
if (!isset($_SESSION['loginid']) || $_SESSION['loginid'] == "") {
    header('Location: ./index.php');
    exit;
}

include "mssql-dbnew.php";

$sctx = isset($_GET['tx']) ? trim($_GET['tx']) : "";
$rowke = isset($_GET['rk']) ? trim($_GET['rk']) : "";
$stmt = null;

$tsql = "SELECT a.m_kode, a.m_nama 
         FROM master_item a";

if ($sctx !== '') {
    $tsql .= " WHERE a.m_nama LIKE '%" . 
	$con_dbnew->real_escape_string($sctx) . "%'";
}

//echo $tsql."<br>";
$stmt = $con_dbnew->query($tsql);

?>

<div id="dialog-listitem" title="Daftar Item">
	<div class="dialog-content-scroll">
		<table class="table table-bordered table-striped table-hover table-condensed">
			<thead>
				<tr>
					<th width="5%">No</th>
					<th width="20%">Kode</th>
					<th width="30%">Nama</th>
			</thead>
			<tbody>
				<?php if ($stmt && $stmt->num_rows > 0): ?>
					<?php 
							$no = 1;
							while ($row = $stmt->fetch_assoc()): ?>
							
							
						<tr>
							<td><?php echo $no++; ?>
							<td onClick="selectitem('<?php echo $rowke; ?>','<?php echo $row['m_kode']; ?>','<?php echo $row['m_nama']; ?>')" style="cursor:pointer"><?php echo $row['m_kode']; ?></td>
							<td onClick="selectitem('<?php echo $rowke; ?>','<?php echo $row['m_kode']; ?>','<?php echo $row['m_nama']; ?>')" style="cursor:pointer"><?php echo $row['m_nama']; ?></td>
						</tr>
					<?php endwhile; ?>
				<?php else: ?>
					<tr><td colspan="4">Data tidak ditemukan</td></tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>