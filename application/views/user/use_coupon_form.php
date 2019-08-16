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
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">쿠폰 사용 페이지</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" action="/admin/check_coupon" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="쿠폰 코드를 입력해주세요." name="coupon_code"  type="text" autofocus>
                                </div>
                                
                                <button type="submit" class="btn btn-lg btn-success btn-block">사용 가능여부 확인</button>
                            </fieldset>
                        </form>
                    </div>

                    <div class="panel-heading">
                        <h3 class="panel-title">쿠폰 유저, 그룹별 사용 내용</h3>
                    </div>

                    <div class="panel-body">
                        
                        <table class="" style="margin-top:20px;">
                            <thead>
                                <tr>
                                    
                                    <td>회원 아이디</td>
                                    <td>그룹 아이디</td>
                                    <td>사용 횟수</td>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($groupCountList as $value) {
                                ?>
                                <tr>
                                    <td><?php echo $value['user_id']; ?></td>
                                    <td><?php echo $value['group_id']; ?></td>
                                    <td><?php echo $value['use_count']; ?></td>
                                    
                                </tr>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
