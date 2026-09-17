<div class="box-login">
	<div class="login-view-website text-sm"><a href="../" target="_blank" title="Xem website"><i class="fas fa-reply mr-2"></i>Xem website</a></div>
	<div class="login-box">
		<div class="card">
			<div class="card-body login-card-body">
				<form class="login">
					<div class="input-group">
						<input type="text" class="form-control" name="username" id="username"  placeholder="Tài khoản" autocomplete="off">
					</div>
					<div class="input-group">
						<input type="password" class="form-control" name="password" id="password"  placeholder="Mật khẩu *" autocomplete="off">
						<div class="input-group-append">
							<div class="input-group-text show-password">
								<span class="fas fa-eye"></span>
							</div>
						</div>
					</div>
					<button type="button" class="btn-login">Đăng nhập</button>
					<div class="alert my-alert alert-login d-none text-center text-sm p-2 mb-0 mt-2" role="alert"></div>
				</form>
			</div>
		</div>
	</div>
</div>
<style>
	.login {
		overflow: hidden;
		background-color: white;
		padding: 40px 30px 30px 30px;
		border-radius: 10px;
		position: absolute;
		top: 50%;
		left: 50%;
		width: 400px;
		-webkit-transform: translate(-50%, -50%);
		-moz-transform: translate(-50%, -50%);
		-ms-transform: translate(-50%, -50%);
		-o-transform: translate(-50%, -50%);
		transform: translate(-50%, -50%);
		-webkit-transition: -webkit-transform 300ms, box-shadow 300ms;
		-moz-transition: -moz-transform 300ms, box-shadow 300ms;
		transition: transform 300ms, box-shadow 300ms;
		box-shadow: 5px 10px 10px rgb(2 128 144 / 20%);
		text-align: center;
	}
	.login::before, .login::after {
		content: "";
		position: absolute;
		width: 600px;
		height: 600px;
		border-top-left-radius: 40%;
		border-top-right-radius: 45%;
		border-bottom-left-radius: 35%;
		border-bottom-right-radius: 40%;
		z-index: -1;
	}
	.login::before {
		left: 40%;
		bottom: -130%;
		background-color: rgba(69, 105, 144, 0.15);
		-webkit-animation: wawes 6s infinite linear;
		-moz-animation: wawes 6s infinite linear;
		animation: wawes 6s infinite linear;
	}
	.login::after {
		left: 35%;
		bottom: -125%;
		background-color: rgba(2, 128, 144, 0.2);
		-webkit-animation: wawes 7s infinite;
		-moz-animation: wawes 7s infinite;
		animation: wawes 7s infinite;
	}
	.login .input-group{
		margin: 15px 0;
	}
	.login .input-group input {
		font-family: "Roboto", sans-serif;
		display: block;
		border-radius: 5px;
		font-size: 16px;
		background: white;
		width: 100%;
		border: 0;
		padding: 10px 10px;
	}
	.login .input-group .input-group-append{
		background: #fff;
		border-top-right-radius: 5px;
		border-bottom-right-radius: 5px;
	}
	.login .input-group .input-group-append .input-group-text{
		border: none;
	}
	.login > button {
		font-family: "Roboto", sans-serif;
		cursor: pointer;
		color: #fff;
		font-size: 15px;
		text-transform: uppercase;
		width: 120px;
		border: 0;
		padding: 12px 0 9px;
		border-radius: 5px;
		background-color: #9f661d;
		-webkit-transition: background-color 300ms;
		-moz-transition: background-color 300ms;
		transition: background-color 300ms;
		margin: 10px auto 0;
		font-weight: 700;
	}
	@keyframes wawes {
		from {transform: rotate(0); }
		to { transform: rotate(360deg); }
	}
	@media (max-width: 480px){
		.login{
			width: 350px;
		}
	}
</style>
<div class="login-copyright text-sm"></div>

<!-- Login js -->
<script type="text/javascript">
	function login()
	{
		var username = $("#username").val();
		var password = $("#password").val();

		if($(".alert-login").hasClass("alert-danger") || $(".alert-login").hasClass("alert-success"))
		{
			$(".alert-login").removeClass("alert-danger alert-success");
			$(".alert-login").addClass("d-none");
			$(".alert-login").html("");
		}
		if($(".show-password").hasClass("active"))
		{
			$(".show-password").removeClass("active");
			$("#password").attr("type","password");
			$(".show-password").find("span").toggleClass("fas fa-eye fas fa-eye-slash");
		}
		$(".show-password").addClass("disabled");
		$(".btn-login .sk-chase").removeClass("d-none");
		$(".btn-login span").addClass("d-none");
		$(".btn-login").attr("disabled",true);
		$("#username").attr("disabled",true);
		$("#password").attr("disabled",true);

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: 'ajax/ajax_login.php',
			async: false,
			data: {username:username,password:password},
			success: function(result)
			{
				if(result.success)
				{
					window.location = "index.php";
				}
				else if(result.error)
				{
					$(".alert-login").removeClass("d-none");
					$(".show-password").removeClass("disabled");
					$(".btn-login .sk-chase").addClass("d-none");
					$(".btn-login span").removeClass("d-none");
					$(".btn-login").attr("disabled",false);
					$("#username").attr("disabled",false);
					$("#password").attr("disabled",false);
					$(".alert-login").removeClass("alert-success");
					$(".alert-login").addClass("alert-danger");
					$(".alert-login").html(result.error);
				}
			}
		});
	}
	$(document).ready(function(){
		$("#username, #password").keypress(function(event){
			if(event.keyCode == 13 || event.which == 13) {
				$(".btn-login").click();
			} 
		})
		$(".btn-login").click(function(){
			login();
		})
		$(".show-password").click(function(){
			if($("#password").val())
			{
				if($(this).hasClass("active"))
				{
					$(this).removeClass("active");
					$("#password").attr("type","password");
				}
				else
				{
					$(this).addClass("active");
					$("#password").attr("type","text");
				}
				$(this).find("span").toggleClass("fas fa-eye fas fa-eye-slash");
			}
		})
	})
	</script>