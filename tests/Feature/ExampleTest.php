<?php

it('directs visitors to plant inventory', function () {
    $this->get('/')->assertRedirect('/plants');
});
