<?php

namespace Util\Controller;

/**
 * Add resource localisation helper methods.
 *
 * @author Robin Chalas <rchalas@sutunam.com>
 */
trait LocalizableTrait
{
    /**
     * Get Bundle path.
     *
     * @param string $shortName
     *
     * @return string
     *
     * @throws \InvalidArgumentException If the bundle doesn't exists
     */
    protected function getBundlePath($name)
    {
        $registeredBundles = $this->get('kernel')->getBundles();
        $bundleMap = array();

        foreach ($registeredBundles as $bundle) {
            $bundleMap[$bundle->getName()] = $bundle->getPath();
        }

        if (false === isset($bundleMap[$name])) {
            throw new \InvalidArgumentException(
                sprintf('Bundle "%s" does not exist or it is not enabled.', $name)
            );
        }

        return $bundleMap[$name];
    }

    /**
     * Get bundle full path from bundle name.
     *
     * @param string $shortName Bundle shortcut name
     *
     * @throws \InvalidArgumentException if the file cannot be found or the name is not valid
     * @throws \RuntimeException         if the name contains invalid/unsafe characters
     *
     * @return string
     */
    protected function getResourcePath($resource)
    {
        if ('@' !== $resource[0]) {
            throw new \InvalidArgumentException(sprintf('A resource name must start with @ ("%s" given).', $resource));
        }

        if (false !== strpos($resource, '..')) {
            throw new \RuntimeException(sprintf('File name "%s" contains invalid characters (..).', $resource));
        }

        return substr($resource, 1);
    }

    /**
     * Returns the file path for a given resource.
     *
     * @param string $name  A resource name to locate
     * @param string $dir   A directory where to look for the resource first
     * @param bool   $first Whether to return the first path or paths for all matching bundles
     *
     * @throws \InvalidArgumentException if the file cannot be found
     *
     * @return string|array The absolute path of the resource or an array if $first
     */
    protected function locateResource($name, $dir = null, $first = true)
    {
        $path = '';
        $bundleName = $this->getResourcePath($name);

        if (false !== strpos($bundleName, '/')) {
            list($bundleName, $path) = explode('/', $bundleName, 2);
        }

        $isResource = 0 === strpos($path, 'Resources') && null !== $dir;
        $overridePath = substr($path, 9);
        $resourceBundle = null;
        $bundles = $this->get('kernel')->getBundle($bundleName, false);
        $files = array();

        foreach ($bundles as $bundle) {
            if ($isResource && file_exists($file = $dir.'/'.$bundle->getName().$overridePath)) {
                if ($first) {
                    return $file;
                }

                $files[] = $file;
            }

            if (file_exists($file = $bundle->getPath().'/'.$path)) {
                if ($first && !$isResource) {
                    return $file;
                }
                $files[] = $file;
                $resourceBundle = $bundle->getName();
            }
        }

        if (count($files) > 0) {
            return $first && $isResource ? $files[0] : $files;
        }

        return;
    }
}
