<?php

function format_rupiah($angka)
{

  $hasil_rupiah = "Rp. " . number_format($angka, 2, ',', '.');
  return $hasil_rupiah;
}


function responserSuccess($variable, $status = 200, $data)
{
  return response()->json([
    'message'   => $variable . ' successfully',
    'data'      => $data
  ], $status);
}


function badResponse($variable, $status = 400)
{
  return response()->json([
    'message'   => $variable . ' not found',
  ], $status);
}
