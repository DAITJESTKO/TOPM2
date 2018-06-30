<meta charset="utf-8">

<?
//require_once("for_form.php"); 
//do_html_header("");
//check_valid_user();
require_once("bookmark_fns.php"); 
  session_start();
$conn = db_connect();
  if (!$conn)
    return 0;
//echo "НАЧАЛОСЬ СРАВНЕНИЕ <br>";

?>
    <? do_html_header("Заводик"); ?>


        <div onclick="tree_toggle(arguments[0])">


            <div class="Content">
            </div>
                <ul class="Container">
                    <form action="add_workshop.php?id_factory=5" onsubmit="NewWindow(this.action,'name','600','600','yes');return false">
                        <input type="text" name="some" value="555">
                        <li class="Node IsRoot ExpandClosed">
                            <div class="Expand"></div>
                            <div class="Content">
                                Филиал в г.Рязань <input type="text" name="id_factory" value="5">
                            </div>
                            <ul class="Container">
                                <li class="Node ExpandLeaf IsLast">
                                    <div class="Expand"></div>
                                    <div class="Content">
                                        <input type="submit" name="submit_w" formmethod="post" value="Добавить цех">
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </form>
                </ul>

        </div>

        <script src="CSS_JS.js" type="text/javascript"></script>

        <script type="text/javascript">
            var win = null;

            function NewWindow(mypage, myname, w, h, scroll) {
                LeftPosition = (screen.width) ? (screen.width - w) / 2 : 0;
                TopPosition = (screen.height) ? (screen.height - h) / 2 : 0;
                settings = 'height=' + h + ',width=' + w + ',top=' + TopPosition + ',left=' + LeftPosition + ',scrollbars=' + scroll + ',resizable'
                win = window.open(mypage, myname, settings)
            }

        </script>



        <? do_html_footer(); ?>
