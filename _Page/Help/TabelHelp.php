<?php
    //koneksi dan session
    include "../../_Config/Connection.php";
    include "../../_Config/Session.php";
    date_default_timezone_set("Asia/Jakarta");


    $jml_data = mysqli_num_rows(mysqli_query($Conn, "SELECT*FROM help"));
?>
<div class="table-responsive">
    <table class="table table-hover table-bordered align-items-center mb-0">
        <thead class="">
            <tr>
                <th class="text-center">
                    <b>No</b>
                </th>
                <th class="text-center">
                    <b>Title</b>
                </th>
                <th class="text-center">
                    <b>Date Time</b>
                </th>
                <th class="text-center">
                    <b>Option</b>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
                if(empty($jml_data)){
                    echo '<tr>';
                    echo '  <td colspan="4" class="text-center">';
                    echo '      <span class="text-danger">No Data</span>';
                    echo '  </td>';
                    echo '</tr>';
                }else{
                    $no=1;
                    $QryKategori = mysqli_query($Conn, "SELECT DISTINCT category FROM help ORDER BY category ASC");
                    while ($DataKategori = mysqli_fetch_array($QryKategori)) {
                        $category=$DataKategori['category'];
                        echo '<tr>';
                        echo '  <td class="text-left text-xs"><b>'.$no.'</b></td>';
                        echo '  <td class="text-left text-xs" colspan="4"><b>'.$category.'</b></td>';
                        echo '</tr>';
                        //LIST HELP
                        $no2=1;
                        $query = mysqli_query($Conn, "SELECT*FROM help WHERE category='$category' ORDER BY title ASC");
                        while ($data = mysqli_fetch_array($query)) {
                            $id_help=$data['id_help'];
                            $category=$data['category'];
                            $title=$data['title'];
                            $description= $data['description'];
                            $datetime= $data['datetime'];
                            $datetime=date('d/m/y H:i',$datetime);
                    ?>
                        <tr>
                            <td class="text-xs" align="right">
                                <?php echo "$no.$no2" ?>
                            </td>
                            <td class="text-left" align="left">
                                <?php 
                                    echo '<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalDetailHelp" data-id="'.$id_help.'">'.$title.'</a><br>';
                                    $QryAkses = mysqli_query($Conn, "SELECT DISTINCT akses FROM help_access WHERE id_help='$id_help'ORDER BY akses ASC");
                                    while ($DataAkses = mysqli_fetch_array($QryAkses)) {
                                        $ListDataAkses=$DataAkses['akses'];
                                        echo '<small class="badge badge-info text-info m-1"><i class="bi bi-check-circle"></i> '.$ListDataAkses.'</small>';
                                    }
                                ?>
                            </td>
                            <td class="text-left text-xs">
                                <?php echo "<small>$datetime</small>" ?>
                            </td>
                            <td align="center">
                                <div class="btn-group">
                                    <a href="javascript:void(0);" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#ModalAksesHelp" data-id="<?php echo "$id_help"; ?>">
                                        <i class="bi bi-person"></i>
                                    </a> 
                                    <a href="index.php?Page=Help&Sub=EditHelp&id=<?php echo $id_help;?>" class="btn btn-success btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>   
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#ModalDeleteHelp" data-id="<?php echo "$id_help"; ?>">
                                        <i class="bi bi-x"></i>
                                    </button>   
                                </div>
                            </td>
                        </tr>
                <?php
                    $no++; }}}
                ?>
        </tbody>
    </table>
</div>