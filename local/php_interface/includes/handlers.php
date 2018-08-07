<?php

AddEventHandler("main", "OnEndBufferContent", "ChangeMyContent");

function ChangeMyContent(&$content) {
  global $USER;
  if (!$USER->IsAdmin()) {
    $content = sanitize_output($content);
  }
}

function sanitize_output($buffer) {
  //return preg_replace('~>\s*\n\s*<~', '><', $buffer);


  $replace = array(
      //remove tabs before and after HTML tags
      '/\>[^\S ]+/s' => '>',
      '/[^\S ]+\</s' => '<',
      //shorten multiple whitespace sequences; keep new-line characters because they matter in JS!!!
      '/([\t ])+/s' => ' ',
      //remove leading and trailing spaces
      '/^([\t ])+/m' => '',
      '/([\t ])+$/m' => '',
      // remove JS line comments (simple only); do NOT remove lines containing URL (e.g. 'src="http://server.com/"')!!!
      '~//[a-zA-Z0-9 ]+$~m' => '',
      //remove empty lines (sequence of line-end and white-space characters)
      '/[\r\n]+([\t ]?[\r\n]+)+/s' => "\n",
      //remove empty lines (between HTML tags); cannot remove just any line-end characters because in inline JS they can matter!
      '/\>[\r\n\t ]+\</s' => '><',
      //remove "empty" lines containing only JS block end character; join with next line (e.g. "}\n}\n</script>" --> "}}</script>"
      '/}[\r\n\t ]+/s' => '}',
      '/}[\r\n\t ]+,[\r\n\t ]+/s' => '},',
      //remove new-line after JS function or condition start; join with next line
      '/\)[\r\n\t ]?{[\r\n\t ]+/s' => '){',
      '/,[\r\n\t ]?{[\r\n\t ]+/s' => ',{',
      //remove new-line after JS line end (only most obvious and safe cases)
      '/\),[\r\n\t ]+/s' => '),',
      //remove quotes from HTML attributes that does not contain spaces; keep quotes around URLs! 
      '~([\r\n\t ])?([a-zA-Z0-9]+)="([a-zA-Z0-9_/\\-]+)"([\r\n\t ])?~s' => '$1$2=$3 $4', //$1 and $4 insert first white-space character found before/after attribute
  );
  $buffer = preg_replace(array_keys($replace), array_values($replace), $buffer);

  //remove optional ending tags (see http://www.w3.org/TR/html5/syntax.html#syntax-tag-omission )
  $remove = array(
      '</option>', '</li>', '</dt>', '</dd>', '</tr>', '</th>', '</td>'
  );
  $buffer = str_ireplace($remove, '', $buffer);
  return $buffer;
}
