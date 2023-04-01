<?php

$FilterOperators = ['eq', 'neq', 'gt', 'gte', 'lt', 'lte', 'like', 'ilike', 'is', 'in', 'cs', 'cd', 'sl', 'sr', 'nxr', 'nxl', 'adj', 'ov', 'fts', 'plfts', 'phfts', 'wfts'];

class PostgrestFilter extends PostgrestTransform
{
    public function eq($column, $value)
    {
        $this->url = $this->url.$column.'=eq.'.$value;
        
        return $this;
    }

    public function neq($column, $value)
    {
        $this->url = $this->url.$column.'=neq.'.$value;

        return $this;
    }

    public function gt($column, $value)
    {
        $this->url = $this->url.$column.'=gt.'.$value;

        return $this;
    }

    public function gte($column, $value)
    {
        $this->url = $this->url.$column.'=gte.'.$value;

        return $this;
    }

    public function lt($column, $value)
    {
        $this->url = $this->url.$column.'=lt.'.$value;

        return $this;
    }

    public function lte($column, $value)
    {
        $this->url = $this->url.$column.'=lte.'.$value;

        return $this;
    }

    public function like($column, $value)
    {
        $this->url = $this->url.$column.'=like.'.$value;

        return $this;
    }

    public function ilike($column, $value)
    {
        $this->url = $this->url.$column.'=ilike.'.$value;

        return $this;
    }

    public function is($column, $value)
    {
        $this->url = $this->url.$column.'=is.'.$value;

        return $this;
    }

    public function in($column, $values)
    {
        $cleanedValues = join('%2C', array_map(function ($s) {
            if (is_string($s) && preg_match('/[,()]/', $s)) {
                return strval('"'.$s.'"');
            } else {
                return strval($s);
            }
        }, $values));

        $this->url = $this->url.$column.'=in.%28'.$cleanedValues.'%29'; 

        return $this;
    }

    public function contains($column, $value)
    {
        if (is_string($value)) {
            $this->url = $this->url.$column.'=cs.'.$value;
        } elseif (is_array($value)) {
            $this->url = $this->url.$column.'=cs.('.join(',', $value).')';
        } else {
            $this->url = $this->url.$column.'=cs.('.json_encode($value).')';
        }

        return $this;
    }

    public function containedBy($column, $value)
    {
        if (is_string($value)) {
            $this->url = $this->url.$column.'=cd.'.$value;
        } elseif (is_array($value)) {
            $this->url = $this->url.$column.'=cd.('.join(',', $value).')';
        } else {
            $this->url = $this->url.$column.'=cd.('.json_encode($value).')';
        }

        return $this;
    }

    public function rangeGt($column, $range)
    {
        $this->url = $this->url.$column.'=sr.'.$range;

        return $this;
    }

    public function rangeGte($column, $range)
    {
        $this->url = $this->url.$column.'=nxl.'.$range;

        return $this;
    }

    public function rangeLt($column, $range)
    {
        $this->url = $this->url.$column.'=sl.'.$range;

        return $this;
    }

    public function rangeLte($column, $range)
    {
        $this->url = $this->url.$column.'=nxr.'.$range;

        return $this;
    }

    public function rangeAdjacent($column, $range)
    {
        $this->url = $this->url.$column.'=adj.'.$range;

        return $this;
    }

    public function overlaps($column, $value)
    {
        if (is_string($value)) {
            $this->url = $this->url.$column.'=ov.'.$value;
        } else {
            $this->url = $this->url.$column.'=ov.('.join(',', $value).')';
        }

        return $this;
    }

    public function textSearch($column, $opts)
    {
        $typePart = '';
        if ($opts->plain) {
            $typePart = 'pl';
        } elseif ($opts->phrase) {
            $typePart = 'ph';
        } elseif ($opts->websearch) {
            $typePart = 'w';
        }

        $configPart = $opts->config ? '('.$opts->config.')' : '';
        $this->url = $this->url.$column.$typePart.'=fts'.$configPart.'.'.$query;

        return $this;
    }

    public function match($query)
    {
        foreach ($query as $column => $value) {
            $this->url = $this->url.$column.'=eq.'.$value;
        }

        return $this;
    }

    public function not($column, $operator, $value)
    {
        $this->url = $this->url.$column.'=not.'.$operator.'.'.$value;

        return $this;
    }

    public function or($filters, $opts)
    {
        $key = $opts->foreignTable ? $opts->foreignTable.'=or.' : '=or.';
        $this->url = $this->url.$key.'%28'.$filters.'%29';

        return $this;

        //finish methods
    }

    public function filter($column, $operator, $value)
    {
        $this->url = $this->url.$column.'='.$operator.'.'.$value;

        return $this;
    }
}
