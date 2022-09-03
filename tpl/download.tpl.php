<?php

if (\is_file(__DIR__ . '/../build/bin/' . $this->subtemplate . '.zip')
    && \stripos(
        \realpath(__DIR__ . '/../build/bin/' . $this->subtemplate . '.zip'),
        \realpath(__DIR__ . '/../')) !== 0
) {
    $fp = \fopen(__DIR__ . '/../build/bin/' . $this->subtemplate . '.zip', 'r');

    \fpassthru($fp);
    \fclose($fp);
}
