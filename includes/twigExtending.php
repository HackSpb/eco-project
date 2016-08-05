<?php
/******
*** расширения для твига
http://twig.sensiolabs.org/doc/advanced.html
http://olesya-lara.livejournal.com/11792.html
http://stackoverflow.com/questions/26170727/how-to-create-a-twig-custom-tag-that-executes-a-callback

***/
class MyTagTokenParser extends \Twig_TokenParser
{

   public function parse(\Twig_Token $token)
   {
      $lineno = $token->getLine();

  		$parser = $this->parser;
        $stream = $parser->getStream();
		$name[] = $stream->expect(Twig_Token::NAME_TYPE)->getValue();
     	 while ( !$stream->test(Twig_Token::BLOCK_END_TYPE))
      	{
        $t=$stream->next()->getValue();
        if($t!='.')$name []=$t; 
		}
		$stream->expect(\Twig_Token::BLOCK_END_TYPE);
       // $stream->expect(Twig_Token::OPERATOR_TYPE, '=');
        $value = '1'; //$parser->getExpressionParser()->parseExpression();
        //$stream->expect(Twig_Token::BLOCK_END_TYPE);

        return new Paste_tag_Node($name, $token->getLine(), $this->getTag());

   }


   public function getTag()
   {
      return 'paste';
   }

}


class Paste_tag_Node extends Twig_Node
{
    public function __construct($name, $line, $tag = null)
    {
        parent::__construct(array('value' => $value), array('name' => $name), $line, $tag);
    }

    public function compile(Twig_Compiler $compiler)
    {
        if(count($this->getAttribute('name'))==1)
        {
        $compiler
            ->addDebugInfo($this)
            ->write(" 

            	echo '".$this->getAttribute('name')[0]."';  if(isset(\$context['". $this->getAttribute('name')[0]. "'])) 
                 	echo \$context['". $this->getAttribute('name')[0]. "'];
                    	")
            ->raw(";\n");
        }else
        {	$str='';
        	 foreach ($this->getAttribute('name') as $value) {
        	 	$str .="['".$value."']";
        	 }
        	  $compiler
            ->addDebugInfo($this)
            ->write(" 

            	 if(isset(\$context ".$str.")) 
                 	echo \$context ".$str.";
                    	")
            ->raw(";\n");
        }
        
    }
}


class MyTagExtension extends \Twig_Extension
{

   public function getTokenParsers()
   {
      return array (
              new MyTagTokenParser(),
      );
   }

   public function getName()
   {
      return 'paste';
   }
}