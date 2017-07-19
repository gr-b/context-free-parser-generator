<?php

/**
 * Class Generator
 *
 * This class is responsible for generating the final php code to be
 * output to the /output/ directory.
 *
 * @author Griffin Bishop <grbishop@wpi.edu>
 * @package ParserGenerator
 */

namespace ParserGenerator;

use Exception;

class Generator
{
    /** @var string $projectName */
    private $projectName;

    /** @var string $projectPath */
    private $projectPath;

    public function __construct($projectName)
    {
        $this->projectName = $projectName;
        $this->projectPath = self::getUnusedProjectPath($projectName);

        echo "Project name: {$this->projectName}\n";
        echo "Project path: {$this->projectPath}\n";
    }

    /**
     * Generates a working autoloader for the produced parser
     * putting in macros.
     *
     * Writes the new autoload.php file to the
     * $this->projectDirectory/$this->projectName directory.
     */
    public function generateAutoloader()
    {
        $path = __DIR__.'/templates/autoloadTemplate.php';
        $template = Filesystem::read($path);

        if (!isset($template)) {
            throw new Exception("Generator: could not load autoloader template file at path: {$path}.\n");
            exit;
        }

        $macros = array('projectName' => $this->projectName);
        $autoloader = $this->resolveMacros($template, $macros);

        Filesystem::write($this->projectPath.'/autoloader.php', $autoloader);
    }

    /**
     * Replaces twig-like macro syntax in the given $template string
     * with macros from the given associative array.
     *
     * @param $template
     * The string (file) to replace macros. Macros look like
     * '{{ macroName }}' where 'macroName' is an entry in the
     * given $macros array.
     * @param $macros
     * Associative array of form: 'macroName' => string value
     *
     * @throws Exception
     *
     * @return string
     * Returns the template having resolved macros.
     */
    private static function resolveMacros($template, $macros)
    {
        if (preg_match("/{{\s*[^({{)(}})]*\s}}/", $template, $matches, PREG_OFFSET_CAPTURE)) {
            $match = $matches[0][0];
            $macro = substr($match, 2, -2);
            $macro = rtrim(trim($macro));

            $offset = $matches[0][1];
            $length = strlen($match);

            if (!isset($macros[$macro])) {
                throw new Exception("Generator: could not resolve macro {$macro} in macro object"
                    .json_encode($macros)."\n");
            }
            $escaped = $macros[$macro];
            $template = substr($template, 0, $offset) . $escaped . substr($template, $offset + $length);
            return self::resolveMacros($template, $macros);
        }
        return $template;
    }


    /**
     * Checks if the given directory exists in the /output/ directory.
     * If it does, return a unique name (add a number)
     *
     * @param $directory
     * @param $tries
     *
     * @return string
     */
    private function getUnusedProjectPath($directory, $tries = 0) {
        $path = __DIR__ . '/output/' . $directory . ($tries ? $tries : '');

        if (file_exists($path)) {
            return $this->getUnusedProjectPath($directory, ++$tries);
        }

        return $path;
    }
}