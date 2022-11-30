<?php
/**
 * Транслите с латиницы на русский
 */

namespace Ela\Analysis\CharFilter;

class TranslitToEnglish
{
    public $key = 'char_filter';
    public $name = 'en_char_filter';

    public function update(array $analysis)
    {
        //фкеу дфьз
        //arte lamp
        return $this->add($analysis, [
            'type' => 'mapping',
            'mappings' => [
                'й => q',
                'ц => w',
                'у => e',
                'к => r',
                'е => t',
                'н => y',
                'г => u',
                'ш => i',
                'щ => o',
                'з => p',
                'х => [',
                'ъ => ]',
                'ф => a',
                'ы => s',
                'в => d',
                'а => f',
                'п => g',
                'р => h',
                'о => j',
                'л => k',
                'д => l',
                'ж => ;',
                'я => z',
                'ч => x',
                'с => c',
                'м => v',
                'и => b',
                'т => n',
                'ь => m',
                'б => ,',
                'ю => .',
                '. => /'
            ],
        ]);
    }

    public function add(array $analysis, array $data)
    {
        $analysis[$this->key][$this->name] = $data;
        return $analysis;
    }
}
