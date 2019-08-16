<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    
    <title>Admin</title>

    <!-- Bootstrap Core CSS -->
    <link href="/assets/sb_admin/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="/assets/sb_admin/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/assets/sb_admin/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="/assets/sb_admin/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery -->
    <script src="/assets/sb_admin/vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="/assets/sb_admin/vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="/assets/sb_admin/vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="/assets/sb_admin/dist/js/sb-admin-2.js"></script>
    <style>
      table {
        width: 100%;
        border: 1px solid #444444;
      }
      th, td {
        border: 1px solid #444444;
      }
    </style>

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">쿠폰 코드 리스트 페이지</h3>
                    </div>
                    <div class="panel-body">
                        <div style="width: 200px; float: right; margin-bottom:20px;">
                            <form id="search-form" action="/admin/coupon_list" method="get">
                                
                                <select id="search_group" name="search_group" class="form-control list_search_element">
                                <option disabled selected >그룹 선택</option>

                                <?php
                                    foreach ($groupList as $value) {
                                ?>
                                    <option value="<?= $value['id']?>" <?php if ($searchGroup == $value['id']){ ?>selected <?php } ?> >
                                        <?= $value['id']?>
                                    </option>
                                <?php
                                }
                                ?>
                                </select>

                            </form>
                        </div>

                        <table class="" style="margin-top:20px;">
                            <thead>
                                <tr>
                                    <td></td>
                                    <td>쿠폰코드</td>
                                    <td>쿠폰코드 사용일시</td>
                                    <td>쿠폰코드 사용유저</td>
                                    <td>그룹번호</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($couponList as $value) {
                                ?>
                                <tr>
                                    <td><?php echo $value['id']; ?></td>
                                    <td><?php echo $value['coupon_code']; ?></td>
                                    <td><?php echo $value['used_datetime']; ?></td>
                                    <td><?php echo $value['user_id']; ?></td>
                                    <td><?php echo $value['group_id']; ?></td>
                                </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <?php echo $pagination; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('#search_group').change(function(){
            $('#search-form').submit();
        });
    </script>

</body>

</html>
