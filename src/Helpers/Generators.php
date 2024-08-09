<?php

namespace Helpers;

use ReflectionClass;
use ReflectionException;

/**
 * Class Generators
 * @package App\Helpers
 *
 * this is a helper class used for generating new resources
 */
class Generators
{

    public static function getStub($type)
	{
		return file_get_contents(resource_path("stubs/$type.stub"));
	}

	/**
	 * appends content to a file already existing in the app folder only
	 * @param string $path
	 * @param string $needle
	 * @param string $content
	 */
	public static function appendInAppFolder($path,$needle,$content)
	{
		file_put_contents(app_path($path), str_replace($needle, $content, file_get_contents(app_path($path))));
	}

	/**
	 * appends content to a file already existing in the resource folder only
	 * @param string $path
	 * @param string $needle
	 * @param string $content
	 */
	public static function appendInResourcesFolder($path,$needle,$content)
	{
		file_put_contents(resource_path($path), str_replace($needle, $content, file_get_contents(resource_path($path))));
	}

	/**
	 * appends content to a file already existing in any of the project folders
	 * @param string $path
	 * @param string $needle
	 * @param string $content
	 */
	public static function appendInProject($path, $needle, $content)
	{
		file_put_contents(base_path($path), str_replace($needle, $content, file_get_contents(base_path($path))));
	}
}