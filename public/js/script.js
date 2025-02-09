$(function () {
  $(".tombolTambahData").on("click", function () {
    $("#judulModalLabel").html("Tambah Data Mahasiswa");
    $(".modal-footer button[type=submit]").html("Tambah Data");
    $("#nama").val("");
    $("#nim").val("");
    $("#jurusan").val("");
    $("#email").val("");
    $(".modal-body form").attr(
      "action",
      "http://localhost/belajar/phpmvc/sesi13/public/mahasiswa/tambah"
    );
  });
  $(".tampilModalUbah").on("click", function () {
    $("#judulModalLabel").html("Ubah Data Mahasiswa");
    $(".modal-footer button[type=submit]").html("Ubah Data");
    $(".modal-body form").attr(
      "action",
      "http://localhost/belajar/phpmvc/sesi13/public/mahasiswa/ubah"
    );

    const id = $(this).data("id");

    // karena objek pake {}
    $.ajax({
      url: "http://localhost/belajar/phpmvc/sesi13/public/mahasiswa/getubah",
      data: { id: id },
      method: "post",
      dataType: "json",
      success: function (data) {
        $("#nama").val(data.nama);
        $("#nim").val(data.nim);
        $("#jurusan").val(data.jurusan);
        $("#email").val(data.email);
        $("#id").val(data.id);
      },
    });
  });
});
