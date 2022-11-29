<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 19.11.2022
 * Time: 13:26
 */

namespace Ela\Facades;


use Elastica\Suggest\AbstractSuggest;
use Illuminate\Support\Facades\Facade;

/**
 * @method static self addSuggestion(AbstractSuggest $suggestion): self
 * @method static self setGlobalText(string $text): self
 *
 * @see \Elastica\Suggest
 */
class Suggest extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'Suggest';
    }
}
