<?php if(!isset($branch)) $branch = 0;?>
<div id='featurebar'>
  <ul class='nav'>
    <?php if(isset($moduleID)):?>
    <li>
      <span>
        <?php
        echo $moduleName;
        if($moduleID)
        {
            $removeLink = $browseType == 'bymodule' ? inlink('browse', "productID=$productID&branch=$branch&browseType=$browseType&param=0&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("caseModule")';
            echo '&nbsp;' . html::a($removeLink, "<i class='icon icon-remove'></i>") . '&nbsp;';
        }
        echo " <i class='icon-angle-right'></i>&nbsp; ";
        ?>
      </span>
    </li>
    <?php endif;?>
    <?php foreach($app->customMenu['featurebar'] as $type => $featurebar):?>
    <?php if($featurebar['status'] == 'hide') continue;?>
    <?php
    if(common::hasPriv('testcase', 'browse') and ($type == 'all' or $type == 'needconfirm'))
    {
        echo "<li id='{$type}Tab'>" . html::a($this->createLink('testcase', 'browse', "productid=$productID&branch=$branch&browseType=$type"), $featurebar['link']) . "</li>";
    }
    elseif($type == 'group' and common::hasPriv('testcase', 'groupcase'))
    {
        echo "<li id='groupTab' class='dropdown'>";
        $groupBy  = isset($groupBy) ? $groupBy : '';
        $current  = zget($lang->testcase->groups, isset($groupBy) ? $groupBy : '', '');
        if(empty($current)) $current = $lang->testcase->groups[''];
        echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown'");
        echo "<ul class='dropdown-menu'>";
        foreach ($lang->testcase->groups as $key => $value)
        {
            if($key == '') continue;
            echo '<li' . ($key == $groupBy ? " class='active'" : '') . '>';
            echo html::a($this->createLink('testcase', 'groupCase', "productID=$productID&branch=$branch&groupBy=$key"), $value);
        }
        echo '</ul></li>';
    }
    elseif($type == 'zerocase' and common::hasPriv('story', 'zeroCase'))
    {
        echo "<li id='zerocaseTab'>" . html::a($this->createLink('story', 'zeroCase', "productID=$productID"), $lang->story->zeroCase) . '</li>';
    }
    ?>
    <?php endforeach;?>
    <?php
    if($this->methodName == 'browse') echo "<li id='bysearchTab'><a href='#'><i class='icon-search icon'></i>&nbsp;{$lang->testcase->bySearch}</a></li> ";
    ?>
  </ul>
  <div class='actions'>
    <div class='btn-group'>
      <div class='btn-group'>
        <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>
          <i class='icon-download-alt'></i> <?php echo $lang->export ?>
          <span class='caret'></span>
        </button>
        <ul class='dropdown-menu' id='exportActionMenu'>
        <?php 
        $misc = common::hasPriv('testcase', 'export') ? "class='export'" : "class=disabled";
        $link = common::hasPriv('testcase', 'export') ?  $this->createLink('testcase', 'export', "productID=$productID&orderBy=$orderBy") : '#';
        echo "<li>" . html::a($link, $lang->testcase->export, '', $misc) . "</li>";

        $misc = common::hasPriv('testcase', 'exportTemplet') ? "class='export'" : "class=disabled";
        $link = common::hasPriv('testcase', 'exportTemplet') ?  $this->createLink('testcase', 'exportTemplet', "productID=$productID") : '#';
        echo "<li>" . html::a($link, $lang->testcase->exportTemplet, '', $misc) . "</li>";
        ?>
        </ul>
      </div>
      <?php 
      common::printIcon('testcase', 'import', "productID=$productID&branch=$branch", '', 'button', '', '', 'export cboxElement iframe');

      $initModule = isset($moduleID) ? (int)$moduleID : 0;
      common::printIcon('testcase', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$initModule");
      common::printIcon('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule");
      ?>
    </div>
  </div>
  <div id='querybox' class='<?php if($browseType =='bysearch') echo 'show';?>'></div>
</div>

<?php
$headerHooks = glob(dirname(dirname(__FILE__)) . "/ext/view/featurebar.*.html.hook.php");
if(!empty($headerHooks))
{
    foreach($headerHooks as $fileName) include($fileName);
}
?>
