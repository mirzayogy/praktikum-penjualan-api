<?php
include_once "../../config/api-header.php";
include_once "jenisbarang.php";

$jenisbarang = new Jenisbarang($db);
$data = json_decode(file_get_contents("php://input"));

$response["success"] = false;
$response["data"] = array();
$response["message"] = "";

if (
    !empty($data->id) &&
    !empty($data->namajenisbarang)
) {

    $jenisbarang->id = $data->id;
    $jenisbarang->namajenisbarang = $data->namajenisbarang;
    
    if ($jenisbarang->update()) {
        $stmt = $jenisbarang->find();
        $num = $stmt->rowCount();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $jenisbarang_item = array(
                "id" => $row["id"],
                "namajenisbarang" => $row["namajenisbarang"]
            );
            array_push($response["data"], $jenisbarang_item);
        }
        http_response_code(201);
        $response["success"] = true;
        $response["message"] = "update data jenisbarang berhasil";
    } else {
        http_response_code(503);
        $response["message"] = "update data jenisbarang gagal";
    }
} else {

    http_response_code(400);
    $response["message"] = "lengkapi data jenisbarang";
}
echo json_encode($response);
