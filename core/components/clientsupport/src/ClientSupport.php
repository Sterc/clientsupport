<?php

namespace Sterc\ClientSupport;

use MODX\Revolution\modX;

class ClientSupport
{
    public $modx = null;
    public $namespace = 'clientsupport';
    public $cache = null;
    public $options = [];

    public function __construct(modX &$modx, array $options = [])
    {
        $this->modx      =& $modx;
        $this->namespace = $this->getOption('namespace', $options, 'clientsupport');

        $corePath   = $this->getOption('core_path', $options, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/clientsupport/');
        $assetsPath = $this->getOption('assets_path', $options, $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/clientsupport/');
        $assetsUrl  = $this->getOption('assets_url', $options, $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/clientsupport/');

        /* loads some default paths for easier management */
        $this->options = array_merge([
            'namespace'         => $this->namespace,
            'corePath'          => $corePath,
            'processorsPath'    => $corePath . 'processors/',
            'chunksPath'        => $corePath . 'elements/chunks/',
            'snippetsPath'      => $corePath . 'elements/snippets/',
            'templatesPath'     => $corePath . 'templates/',
            'assetsPath'        => $assetsPath,
            'assetsUrl'         => $assetsUrl,
            'jsUrl'             => $assetsUrl . 'js/',
            'cssUrl'            => $assetsUrl . 'css/',
            'connectorUrl'      => $assetsUrl . 'connector.php'
        ], $options);

        $this->modx->lexicon->load('clientsupport:default');
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption($key, $options = [], $default = null)
    {
        $option = $default;
        if (!empty($key) && is_string($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            } elseif (array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            } elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}.{$key}");
            }
        }

        return $option;
    }

    /**
     * Gets a Chunk and caches it; also falls back to file-based templates
     * for easier debugging.
     *
     * @access public
     * @param string $name The name of the Chunk
     * @param array $properties The properties for the Chunk
     * @return string The processed content of the Chunk
     */
    public function getChunk($name, $properties = [])
    {
        $chunk = null;
        if (!isset($this->chunks[$name])) {
            $chunk = $this->getTplChunk($name);
            if (empty($chunk)) {
                $chunk = $this->modx->getObject('modChunk', ['name' => $name], true);
                if ($chunk == false) {
                    return false;
                }
            }

            $this->chunks[$name] = $chunk->getContent();
        } else {
            $content = $this->chunks[$name];
            $chunk   = $this->modx->newObject('modChunk');

            $chunk->setContent($content);
        }

        $chunk->setCacheable(false);

        return $chunk->process($properties);
    }

    /**
     * Returns a modChunk object from a template file.
     *
     * @access private
     * @param string $name The name of the Chunk. Will parse to name.chunk.tpl
     * @param string $postFix
     * @return modChunk/boolean Returns the modChunk object if found, otherwise
     * false.
     */
    private function getTplChunk($name, $postFix = '.chunk.tpl')
    {
        $chunk = false;
        $file = $this->options['chunksPath'] . strtolower($name) . $postFix;
        if (file_exists($file)) {
            $content = file_get_contents($file);

            /** @var modChunk $chunk */
            $chunk = $this->modx->newObject('modChunk');
            $chunk->set('name', $name);
            $chunk->setContent($content);
        }

        return $chunk;
    }
}
