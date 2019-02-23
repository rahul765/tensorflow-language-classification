		<!-- Start post #1 -->
		<div class="post" id="1">
			<h2>JS Optional Parameters</h2>
			<p>28 March 2014</p>
			<p>A well known tip: optional parameters. In JavaScript there is no syntax to denote optional parameters as in PHP or other languages. Therefore you have to do the leg work yourself:</p>
			<pre class="line-numbers"><code class="language-javascript">var testFunc = function(arg1){
	//If arg1 is undefined, will now default to an empty object
	arg1 = arg1 || {};	
};</code></pre>
			<p>MDN has a nice definition of this behaviour:</p>
			<blockquote><code>expr1 || expr2</code> - (Logical OR) Returns <code>expr1</code> if it can be converted to true; otherwise, returns <code>expr2</code>. Thus, when used with Boolean values, <code>||</code> returns true if either operand is true; if both are false, returns false.<br />...<br /><ul><li>false && anything is short-circuit evaluated to false.</li>
<li>true || anything is short-circuit evaluated to true.</li></ul> ... Note that the anything part of the above expressions is not evaluated, so any side effects of doing so do not take effect.</blockquote>
			Source: <a href="https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Expressions_and_Operators#Logical_operators">Logical Operators - Mozilla Developer Network</a>
		</div>
		<!-- End post #1 -->
