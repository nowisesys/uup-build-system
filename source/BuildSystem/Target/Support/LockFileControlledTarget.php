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

namespace UUP\BuildSystem\Target\Support;

use UUP\BuildSystem\Target\TargetBase;

/**
 * Support class for lockfile controlled builds.
 *
 * Should be used along with one of the build always, create once or update if
 * modified classes. Derive your target class from either one of these to create a
 * lockfile controlled target.
 *
 * You need to implement to perform() method with the action defining your target
 * class. See docs/targets.md for details.
 *
 * @author Anders Lövgren (Nowise Systems)
 */
abstract class LockFileControlledTarget extends TargetBase
{
    /**
     * Use build directory in this package as lockfile location.
     */
    const USE_BUILD_PATH_DIR = 'build';

    /**
     * Use system temp directory as lockfile location.
     */
    const USE_BUILD_PATH_TMP = 'temp';

    /**
     * @var string Some data file.
     */

    private string $filename;
    /**
     * @var string The default lockfile location.
     */

    private string $location;
    /**
     * @var string The lockfile path.
     */

    private string $lockfile;
    /**
     * @var string The last build file path.
     */

    private string $lasttime;

    /**
     * Constructor.
     * @param string $filename Some data file.
     * @param string $location The lockfile location (USE_BUILD_PATH_XXX constant or directory).
     */
    public function __construct(string $filename, string $location = self::USE_BUILD_PATH_DIR)
    {
        $this->filename = $filename;
        $this->location = $location;
    }

    /**
     * Get path of data file.
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * Get lockfile path.
     * @return string
     */
    public function getLockFilePath(): string
    {
        return $this->lockfile;
    }

    /**
     * Set lockfile path.
     * @param string $lockfile
     */
    public function setLockFilePath(string $lockfile): void
    {
        $this->lockfile = $lockfile;
    }

    /**
     * Get last build file path.
     * @return string
     */
    public function getLastTimePath(): string
    {
        return $this->lasttime;
    }

    /**
     * Set last build file path.
     * @param string $lasttime
     */
    public function setLastTimePath(string $lasttime): void
    {
        $this->lasttime = $lasttime;
    }

    /**
     * Check whether build step is running.
     * @return bool
     */
    public function isLocked(): bool
    {
        return file_exists($this->lockfile);
    }

    /**
     * Get last build time.
     * @return int The UNIX timestamp.
     */
    public function getLastRun(): int
    {
        if (!file_exists($this->lasttime)) {
            return 0;
        } else {
            return filemtime($this->lasttime);
        }
    }

    /**
     * @inheritdoc
     */
    public function isUpdated(): bool
    {
        return file_exists($this->lasttime) && filemtime($this->filename) <= filemtime($this->lasttime);
    }

    /**
     * @inheritdoc
     */
    public function rebuild(): void
    {
        if ($this->isLocked()) {
            return;
        }

        try {
            touch($this->lockfile);
            $this->perform();
            touch($this->lasttime);
        } finally {
            unlink($this->lockfile);
        }
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return basename($this->filename);
    }

    /**
     * @inheritdoc
     */
    public function getType(): string
    {
        return "lockfile";
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return "Lockfile controlled target";
    }

    /**
     * The action to perform for this target.
     */
    abstract protected function perform(): void;

    /**
     * Get filepath relative location.
     * @param string $filename The filename.
     * @return string
     */
    protected function getFilepath(string $filename): string
    {
        return sprintf("%s/%s", $this->getLocation(), basename($filename));
    }

    /**
     * Get location directory path.
     * @return string
     */
    protected function getLocation(): string
    {
        switch ($this->location) {
            case self::USE_BUILD_PATH_DIR:
                return sprintf("%s/%s", __DIR__, '../../../../build');
            case self::USE_BUILD_PATH_TMP:
                return sys_get_temp_dir();
            default:
                return $this->location;
        }
    }
}
