<?php

namespace Flysap\Media;

/**
 * Return media ..
 *
 * @param array $tags
 * @return bool
 */
function media(array $tags = []) {
    return (new Media)
        ->tag($tags);
}