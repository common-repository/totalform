<?php

namespace TotalForm\Tasks\Blocks;
! defined( 'ABSPATH' ) && exit();


use TotalForm\Blocks\Choices;
use TotalForm\Blocks\Date;
use TotalForm\Blocks\Dropdown;
use TotalForm\Blocks\Email;
use TotalForm\Blocks\Embed;
use TotalForm\Blocks\File;
use TotalForm\Blocks\Heading;
use TotalForm\Blocks\Hidden;
use TotalForm\Blocks\Image;
use TotalForm\Blocks\Matrix;
use TotalForm\Blocks\MultipleChoices;
use TotalForm\Blocks\Number;
use TotalForm\Blocks\Paragraph;
use TotalForm\Blocks\Rating;
use TotalForm\Blocks\RawHTML;
use TotalForm\Blocks\Scale;
use TotalForm\Blocks\Spacer;
use TotalForm\Blocks\Text;
use TotalForm\Blocks\TextArea;
use TotalForm\Blocks\URL;
use TotalFormVendors\TotalSuite\Foundation\Exceptions\Exception;
use TotalFormVendors\TotalSuite\Foundation\Task;

/**
 * Class RegisterBlockTypes
 *
 * @method static void invoke()
 * @method static void invokeWithFallback($fallback)
 */
class RegisterDefaultBlockTypes extends Task
{
    /**
     * @inheritDoc
     */
    protected function validate()
    {
        return true;
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function execute()
    {
        Text::register();
        URL::register();
        File::register();
        Hidden::register();
        Email::register();
        TextArea::register();
        Matrix::register();
        Date::register();
        Number::register();
        Choices::register();
        MultipleChoices::register();
        Scale::register();
        Rating::register();
        Dropdown::register();

        Heading::register();
        Paragraph::register();
        Image::register();
        Embed::register();
        RawHTML::register();
        Spacer::register();
    }
}
