<?php

/*
 * This file is part of Chevere.
 *
 * (c) Rodolfo Berrios <rodolfo@chevere.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

xr(['Hola, mundo!', new stdClass()]);
xr(f: '🤔');
xr(t: 'Win');
xr(
    getrusage(),
    f: '😎',
    t: 'Epic win!'
);
