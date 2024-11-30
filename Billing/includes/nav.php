<style>
  * {
    margin: 0;
    box-sizing: border-box;
  }

  a {
    text-decoration: none;
    color: black;
  }

  .nav1 {
    margin-top: 5px;
    background: #fff;
    padding: 30px 200px;
    border: solid black 3px;
  }

  .nav1 .box {
    display: flex;
    font-weight: 600;
    justify-content: space-between;
    gap: 30px;
  }

  .box a {
    border-radius: 10px;
    border: solid black;
    padding: 5px;
  }

  .box a:hover {
    background-color: black;
    color: #fff;
  }
  .nav1 a.active {
  background-color: black;
  color: #fff;
}
</style>
<section class="container1">
  <div class="nav1">
    <div class="box">
      <a href="/idandbilling/billing/">INVOICE</a>
      <a href="/idandbilling/billing/editItems/">EDIT ITEM</a>
      <a href="#">EDIT SELLER INFO</a>
      <a href="#">CUSTOMER DETAIL</a>
    </div>
  </div>
</section>
