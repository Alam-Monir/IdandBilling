<div class="d-flex justify-content-between px-4 pt-4">
    <div class="d-flex">
        <a href="/idandbilling/" class="pt-2 px-2">
            <button class="btn btn-outline-primary" type="button">
                <i class="bi bi-house"></i> Home
            </button>
        </a>
        <a href="/idandbilling/settings/" class=" px-2">
            <i class="bi bi-gear" style="font-size: 2.1rem;"></i>
        </a>
        <h1 id="dynamicHeadLink">Settings</h1>
    </div>
    <!-- <a id="dynamicButtonLink" href=""> -->

        <button id="dynamicButton" class="btn btn-outline-primary m-2" data-bs-toggle="modal" data-bs-target="#exampleModal" type="button">Create New Staff</button>
       
        <!-- Button trigger modal -->
     

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Staff Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      
      <div class="form-floating w-50 position-relative">
            <input type="text" id="text" class="form-control mt-3" placeholder="text">
            <label for="text">First Name</label>
        </div>
        <div class="form-floating w-50 position-absolute  top-0 end-0 mt-3">
            <input type="text" id="text" class="form-control mt-3" placeholder="text">
            <label for="text">Last Name</label>
        </div>

        <div class="form-floating w-100 mt-3">
            <input type="number" id="number" class="form-control mt-3" placeholder="number">
            <label for="number">Phone Number</label>
        </div>
        

        <div class="form-floating w-100">
            <input type="email" id="customerName" class="form-control mt-3" placeholder="email">
            <label for="email">Email</label>
        </div>

        <div class="form-floating w-100">
            <input type="password" id="customerName" class="form-control mt-3" placeholder="password">
            <label for="password">Password</label>
        </div>
        <div class="form-floating w-100">
            <input type="password" id="customerName" class="form-control mt-3" placeholder="password">
            <label for="password"> Confirm Password</label>
        </div>


      </div>

      <div class="modal-footer justify-content-between">
      <button type="button" class="btn btn-primary">Create</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>

    </div>
  </div>
</div>

    
</div>
<hr class="border border-secondary border-2 opacity-75" />