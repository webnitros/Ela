<?php
/**
 * Транслите с латиницы на русский
 */

namespace Ela\Analysis\CharFilter;

class TranslitToRussia
{
    public $key = 'char_filter';
    public $name = 'ru_char_filter';

    public function update(array $analysis)
    {
        return $this->add($analysis, [
            'type' => 'mapping',
            'mappings' => [
                "q => й",
                "w => ц",
                "e => у",
                "r => к",
                "t => е",
                "u => г",
                "i => ш",
                "o => щ",
                "p => з",
                "[ => х",
                "] => ъ",
                "a => ф",
                "s => ы",
                "d => в",
                "f => а",
                "g => п",
                "h => р",

                "k => л",
                "l => д",
                "; => ж",
                "z => я",
                "x => ч",
                "c => с",
                "v => м",
                "b => и",
                "n => т",
                "m => ь",
                ", => б",
                ". => ю",
                "/ => .",
                "y => н",
                "j => о",
            ],
        ]);
    }

    public function add(array $analysis, array $data)
    {
        $analysis[$this->key][$this->name] = $data;
        return $analysis;
    }
}
