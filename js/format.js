PSmileyTag = new Array(6);
PSmileyTag[0] = 0;
PSmileyTag[1] = 0;
PSmileyTag[2] = 0;
PSmileyTag[3] = 0;
PSmileyTag[4] = 0;
PSmileyTag[6] = 0;
PSmileyTag[8] = 0;
PSmileyTag[10] = 0;
function insertSmiley (SelectedSmiley)
{
    var preval=document.getElementById('frmbookForm-zprava').value;

    switch (SelectedSmiley)
    {
        case 1000:
            if (!PSmileyTag[0])
            {
                document.images["PS0"].src = "../images/code_on.png";
                document.getElementById('frmbookForm-zprava').value=preval+"[kod]";
            }
            else
            {
                document.images["PS0"].src = "../images/code.png";
                document.getElementById('frmbookForm-zprava').value=preval+"[/kod]";
            }
            PSmileyTag[0] = !PSmileyTag[0];
            break;
        case 1002:
            if (!PSmileyTag[2])
            {
                document.images["PS2"].src = "../images/bold_on.png";
                document.getElementById('frmbookForm-zprava').value=preval+"[b]";
            }
            else
            {
                document.images["PS2"].src = "../images/bold.png";
                document.getElementById('frmbookForm-zprava').value=preval+"[/b]";
            }
            PSmileyTag[2] = !PSmileyTag[2];
            break;
        case 1004:
            if (!PSmileyTag[4])
            {
                document.images["PS4"].src = "../images/i_on.png";
                document.getElementById('frmbookForm-zprava').value=preval+"[i]";
            }
            else
            {
                document.images["PS4"].src = "../images/i.png";
                document.getElementById('frmbookForm-zprava').value=preval+"[/i]";
            }
            PSmileyTag[4] = !PSmileyTag[4];
            break;
        case 1006:
            if (!PSmileyTag[6])
            {
                document.images["PS6"].src = "../images/r_on.png";
                document.getElementById('frmbookForm-zprava').value=preval+"[red]";
            }
            else
            {
                document.images["PS6"].src = "../images/r.png";
                document.getElementById('frmbookForm-zprava').value=preval+"[/]";
            }
            PSmileyTag[6] = !PSmileyTag[6];
            break;
        case 1008:
            if (!PSmileyTag[8])
            {
                document.images["PS8"].src = "../images/g_on.png";
                document.getElementById('frmbookForm-zprava').value=preval+"[green]";
            }
            else
            {
                document.images["PS8"].src = "../images/g.png";
                document.getElementById('frmbookForm-zprava').value=preval+"[/]";
            }
            PSmileyTag[8] = !PSmileyTag[8];
            break;
        case 1010:
            if (!PSmileyTag[10])
            {
                document.images["PS10"].src = "../images/b_on.png";
                document.getElementById('frmbookForm-zprava').value=preval+"[blue]";
            }
            else
            {
                document.images["PS10"].src = "../images/b.png";
                document.getElementById('frmbookForm-zprava').value=preval+"[/]";
            }
            PSmileyTag[10] = !PSmileyTag[10];
            break;
    }

    document.getElementById('frmbookForm-zprava').focus();

    return false;
}


