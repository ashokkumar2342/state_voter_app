<!DOCTYPE html>
<html>
<head>
<style>
 table,th, td {
  border: 1px solid black;
  border-collapse:collapse;
  text-align:center;
 
}
@page { footer: html_otherpagesfooter; 
	    header: html_otherpageheader;
	}
</style>
</head>
<body>
	<htmlpagefooter name="otherpagesfooter" style="display:none">
		<div style="text-align:right;">
			{nbpg}  {PAGENO}
		</div>
	    
	</htmlpagefooter>
	<htmlpageheader name="otherpageheader" style="display:none">
		<table width = "100%">
			<tbody>
				<tr>
					<td style="width: 100%;background-color: #767d78;color: #fff;text-align: center;"><b>{{ $report_header }}</b></td>
				</tr>
			</tbody>
		</table>			 
	</htmlpageheader>
