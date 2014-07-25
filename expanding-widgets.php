<?php
/*
Plugin Name: Expanding Widgets
Plugin URI: http://mattclements.co.uk/
Description: Expanding Widget system, allows multiple widgets to be created and expanded
Version: 1.0
Author: Matt Clements
Author URI: http://mattclements.co.uk/
License: MIT
*/

class ExpandingWidgets extends WP_Widget
{
	private $_max_widgets = 6;

	function ExpandingWidgets()
	{
		$widget_ops = array('classname' => 'ExpandingWidgets', 'description' => 'Expanding Widget System');
    	$this->WP_Widget('ExpandingWidgets', 'Expanding Widget System', $widget_ops);
	}

	//Widget Form
	function form($instance)
	{

		$settings = array();
		$settings['allow_multiple_open'] = false;
		for($i=1;$i<=$this->_max_widgets;$i++)
		{
			$settings['title_'.$i] = '';
			$settings['enabled_'.$i] = false;
			$settings['text_'.$i] = '';
		}

		$instance = wp_parse_args( (array) $instance, $settings);

		echo "<p><label for=\"".$this->get_field_id('allow_multiple_open')."\">Allow Multiple Widgets Open? <input class=\"widefat\" id=\"".$this->get_field_id('allow_multiple_open')."\" name=\"".$this->get_field_name('allow_multiple_open')."\" type=\"checkbox\" value=\"1\"";
		if($instance['allow_multiple_open']===true)
			echo " checked=\"checked\"";
		echo " /></label></p>";

		echo "<hr />";

		for($i=1;$i<=$this->_max_widgets;$i++)
		{
			echo "<h3>Widget #".$i."</h3>";

			echo "<p><label for=\"".$this->get_field_id('title_'.$i)."\">Title: <input class=\"widefat\" id=\"".$this->get_field_id('title_'.$i)."\" name=\"".$this->get_field_name('title_'.$i)."\" type=\"text\" value=\"".attribute_escape($instance['title_'.$i])."\" /></label></p>";
			echo "<p><label for=\"".$this->get_field_id('enabled_'.$i)."\">Enabled? <input class=\"widefat\" id=\"".$this->get_field_id('enabled_'.$i)."\" name=\"".$this->get_field_name('enabled_'.$i)."\" type=\"checkbox\" value=\"1\"";
			if($instance['enabled_'.$i]===true)
				echo " checked=\"checked\"";
			echo " /></label></p>";
			echo "<p><label for=\"".$this->get_field_id('text_'.$i)."\">Text: <textarea class=\"widefat\" rows=\"5\" id=\"".$this->get_field_id('text_'.$i)."\" name=\"".$this->get_field_name('text_'.$i)."\">".attribute_escape($instance['text_'.$i])."</textarea></label></p>";

			if($i!==$this->_max_widgets)
				echo "<hr />";
		}
	}

	//Widget Form Update
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		if(isset($new_instance['allow_multiple_open']) && intval($new_instance['allow_multiple_open'])===1)
			$instance['allow_multiple_open'] = true;
		else
			$instance['allow_multiple_open'] = false;

		for($i=1;$i<=$this->_max_widgets;$i++)
		{
			$instance['title_'.$i] = $new_instance['title_'.$i];
			$instance['text_'.$i] = $new_instance['text_'.$i];

			if(isset($new_instance['enabled_'.$i]) && intval($new_instance['enabled_'.$i])===1)
				$instance['enabled_'.$i] = true;
			else
				$instance['enabled_'.$i] = false;
		}

	    return $instance;
	}

	//Widget Front-End Display
	function widget($args, $instance)
	{
		$found_widget = " active";

		$expand_type = "open-one";

		if($instance['allow_multiple_open']===true)
		{
			$expand_type = "open-many";
			$found_widget = "";
		}

		echo "<section class=\"expand-section\" data-type=\"".$expand_type."\">";
		for($i=1;$i<=$this->_max_widgets;$i++)
		{
			if($instance['enabled_'.$i]===true)
			{
				echo "<div class=\"expand-section-title".$found_widget."\" data-expand-id=\"".$i."\">
			      <a href=\"#\">".$instance['title_'.$i]."</a>";
				if($found_widget===" active" && $expand_type==="open-one")
					echo "<span class=\"expand-mode minus\">-</span>";
				else
			    	echo "<span class=\"expand-mode plus\">+</span>";
			    echo "</div>
			    <div class=\"expand-section-content".$found_widget."\" data-expander-id=\"".$i."\">
				".do_shortcode($instance['text_'.$i])."
			    </div>";

				$found_widget = "";
			}
		}
		echo "</section>";

		wp_enqueue_script('expanding-widgets', plugins_url('js/expanding-widgets.js', __FILE__), array('jquery'), '1.0.0', true);
	}
}

function register_expandingwidgets_widget()
{
	return register_widget('ExpandingWidgets');
}


// register widget
add_action('widgets_init', 'register_expandingwidgets_widget');
