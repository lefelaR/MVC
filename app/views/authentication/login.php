<?php


?>

<section id="login">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="text-center form-parent">
                    <form class="mt-5" id="login" action="authentication/postLogin">
                        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
                        <div class="card mb-3">
                            <div class="card-body">
                        <div class="form-floating p-1">
                            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                            <label for="floatingInput">Email address</label>
                        </div>
                                <div class="form-floating p-1">
                                    <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
                                    <label for="floatingPassword">Password</label>
                                </div>
                                <div class="checkbox mb-1">
                                    <label>
                                        <input type="checkbox" value="remember-me"> Remember me
                                    </label> |
                                    <label>
                                        <a class="text-muted" href="#">Dont have a login? Signup</a>
                                    </label>
                                </div>

                            </div>
                        
                        </div>
                        <button class="w-100 btn btn-lg btn-primary" type="submit" id="submit">Sign in</button>
                        <p class="mt-5 mb-3 text-muted"><a href="<?php echo $siteroot; ?>" class="text-muted">Home</a> © <?php echo date("Y")?></p>
                    </form>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="side">
                    <!-- <p class=" my-auto "> login with demo credentials</p> -->
                </div>
            </div>
        </div>
    </div>
</section>


