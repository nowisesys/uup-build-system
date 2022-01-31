<?php

/*
 * Copyright (C) 2022 Anders Lövgren (Nowise Systems).
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace UUP\BuildSystem\Target\Traits;

/**
 * Build directory relocation.
 *
 * Relocates the build directory to project root. The default is to keep build
 * artifacts inside the vendor directory. Use this trait in your target classes,
 * preferable some base class.
 */
trait LocationTrait
{
    protected function getLocation(): string
    {
        return sprintf("%s/%s", __DIR__, '../../../../../../../build');
    }
}
