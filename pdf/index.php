<?php 
session_start();
require "fpdf/fpdf.php";
error_reporting(0);
if($_SESSION["tipo_de_usuario"] == "super-usuario" or $_SESSION["tipo_de_usuario"] == "usuario")
{
class PDF extends FPDF 
{
	//logos e institución, título
	function Header()
	{
		$this->image("logos/ciencias_logo_3.png",10,10,20);
		$this->image("logos/ucv_logo_2.png",32,10,22);
		$this->image("logos/logoquimica.png",174,12,26);
		$this->SetFont("times","B",12);
		$this->setXY(55,10);
		$this->Cell(100,5,"Universidad Central de Venezuela",0,1,"C");
		$this->setX(55);
		$this->Cell(100,5,"Facultad de Ciencias",0,1,"C");
		$this->setX(55);
		$this->Cell(100,5,utf8_decode("Escuela de Química"),0,1,"C");
		$this->setX(55);
		$this->Cell(100,5,utf8_decode("Depósito"),0,1,"C");
		$this->setXY(55,40);
		$this->Cell(100,5,utf8_decode("Petición de productos"),0,1,"C");
		$this->ln(20);
		
		//$this->ln(5);
		// $this->cell(80);
		// $this->cell(30,0,"Facultad de Ciencias",0,1,"C");
		// $this->cell(80);
		// $this->cell(30,0,"Escuela de Química",0,1,"C");
	}

	function footer()
	{
		$this->setY(-15);
		//$this->Line(90, 45, 110-10, 45);
		$this->Line(20, 265, 210-20, 265);
	}
	//tabla de pedidos
	function headerTable()
	{
		$this->SetFont("times","B",12);
		$this->setX(34);
		$this->SetFillColor(205);
		$this->cell(45,5,"Producto",1,0,"C",1);
		$this->cell(30,5,utf8_decode("Ubicación"),1,0,"C",1);
		$this->cell(30,5,"Estado",1,0,"C",1);
		$this->cell(40,5,"Cantidad solicitada",1,0,"C",1);
		$this->ln();
	}

	function viewtable()
	{
		$this->SetFont("times","",11);
		foreach ($_SESSION["cart"] as $c) 
		{
			$this->setX(34);
			if($c["type"] == "q")
			{
				$this->cell(45,8,utf8_decode($c["nombre"]),1,0,"C");
				$this->cell(30,8,$c["ubicacion"],1,0,"C");
				$this->cell(30,8,utf8_decode($c["estado"]),1,0,"C");
				$this->cell(40,8,$c["q"],1,0,"C");
				$this->ln();
			}
			else
			{
				$this->cell(45,8,$c["nombre"],1,0,"C");
				$this->cell(30,8,$c["ubicacion"],1,0,"C");
				$this->cell(30,8,"-",1,0,"C");
				$this->cell(40,8,$c["q"],1,0,"C");
				$this->ln();
			}
		}
	}
}
$pdf = new PDF();
$pdf->AddPage();
$pdf->headerTable();
$pdf->viewtable();
$pdf->OutPut();
}
else
{
	header("location:../".$_SERVER['HTTP_REFERER']);
}
?>