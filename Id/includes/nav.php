<div class="d-flex justify-content-between px-4 pt-4">
    <div class="d-flex">
        <a href="/idandbilling/" class="pt-2 px-2">
            <button class="btn btn-outline-primary" type="button">
                <i class="bi bi-house"></i> Home
            </button>
        </a>
        <a href="/idandbilling/Id/" class=" px-2">
        <i class="bi bi-person-badge-fill" style="font-size: 2.1rem;"></i>
        </a>
        <h1 id="dynamicHeadLink"></h1>
    </div>
    <a id="dynamicButtonLink" href="/idandbilling/Id/create/">
        <button id="dynamicButton" class="btn btn-outline-primary m-2" type="button"></button>
    </a>
</div>
<hr class="border border-secondary border-2 opacity-75" />


<script>
    const currentPath = window.location.pathname;

    const button = document.getElementById('dynamicButton');
    const buttonLink = document.getElementById('dynamicButtonLink');
    const head = document.getElementById('dynamicHeadLink');

    if (currentPath === '/idandbilling/Id/') {
        button.textContent = 'Create Layout';
        buttonLink.href = '/idandbilling/Id/create/';
        head.textContent = 'Id Layouts';
    } else if (currentPath === '/idandbilling/Id/edit/') {
        button.textContent = 'ID Page';
        buttonLink.href = '/idandbilling/Id/';
        head.textContent = ' Edit Id Layout';
    } else if (currentPath === '/idandbilling/Id/populate/') {
        button.textContent = 'ID Page';
        buttonLink.href = '/idandbilling/Id/';
        head.textContent = ' Fill Details';
    } else {
        button.textContent = 'ID Page';
        buttonLink.href = '/idandbilling/Id/';
        head.textContent = 'Create Id Layout';
    }
</script>