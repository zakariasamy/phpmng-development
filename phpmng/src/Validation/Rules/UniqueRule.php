<?php
namespace Phpmng\Validation\Rules;
use Rakit\Validation\Rule;
use Phpmng\Database\Database;

class UniqueRule extends Rule
{
    protected $message = ":attribute :value has been used";

    protected $fillableParams = ['table', 'column', 'except'];


    public function check($value): bool
    {
        // make sure required parameters exists
        $this->requireParameters(['table', 'column']);

        // getting parameters
        $column = $this->parameter('column');
        $table = $this->parameter('table');
        $except = $this->parameter('except');

        if ($except AND $except == $value) {
            return true;
        }

        // do query
        $data = Database::table($table)->where($column, '=', $value)->first();
        return $data ? false : true;

        // true for valid, false for invalid
        return intval($data['count']) === 0;
    }
}