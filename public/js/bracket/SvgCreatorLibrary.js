/// <reference path="SvgManip.js" />

//Copyright 2012-2017, Clever Prototypes, LLC
// ALL RIGHTS RESERVED

function SvgCreatorLibrary(id)
{
    this.NameSpace_Svg = "http://www.w3.org/2000/svg";
    this.NameSpace_Xlink = "http://www.w3.org/1999/xlink";

    
    this.AddCircle = function (x, y, r, strokeColor, fillColor, id, cssClass)
    {
        var shape = document.createElementNS(this.NameSpace_Svg, "circle");
        shape.setAttributeNS(null, "cx", x);
        shape.setAttributeNS(null, "cy", y);
        shape.setAttributeNS(null, "r", r);
        shape.setAttributeNS(null, "fill", fillColor);
        shape.setAttributeNS(null, "stroke", strokeColor);
        shape.setAttribute("id", id);
        shape.setAttribute("class", cssClass);

        return shape;
    };

    this.UpdateCircle = function (circle, x, y)
    {
        circle.attr("cx", x);
        circle.attr("cy", y);
    }

    
    this.AddCircle = function (x, y, r, strokeColor, fillColor, id, cssClass)
    {
        var shape = document.createElementNS(this.NameSpace_Svg, "circle");
        shape.setAttributeNS(null, "cx", x);
        shape.setAttributeNS(null, "cy", y);
        shape.setAttributeNS(null, "r", r);
        shape.setAttributeNS(null, "fill", fillColor);
        shape.setAttributeNS(null, "stroke", strokeColor);
        shape.setAttribute("id", id);
        shape.setAttribute("class", cssClass);

        return shape;
    };

    this.UpdateCircle = function (circle, x, y)
    {
        circle.attr("cx", x);
        circle.attr("cy", y);
    }

    this.AddEllipse = function(x, y, rx, ry , strokeColor, fillColor,strokeDash, opacity, id, cssClass)
    {
        var shape = document.createElementNS(this.NameSpace_Svg, "ellipse");
        shape.setAttributeNS(null, "cx", x);
        shape.setAttributeNS(null, "cy", y);
        shape.setAttributeNS(null, "rx", rx);
        shape.setAttributeNS(null, "ry", ry);
        shape.setAttributeNS(null, "fill", fillColor);
        shape.setAttributeNS(null, "stroke", strokeColor);
        shape.setAttributeNS(null, "stroke-dasharray", strokeDash); 
        shape.setAttributeNS(null, "opacity", opacity);
        shape.setAttribute("id", id);
        shape.setAttribute("class", cssClass);

        return shape;
    };

    this.UpdateEllipse = function( Ellipse , x , y , rx , ry )
    {
        Ellipse.attr( "cx", x  );
        Ellipse.attr( "cy", y  );
        Ellipse.attr( "rx", rx );
        Ellipse.attr( "ry", ry );
    }


    this.AddRect = function (x, y, width, height, strokeColor, fillColor, strokeDash, opacity, id)
    {
        x = isNaN(x) ? 10 : x;
        y = isNaN(y) ? 10 : y;
        width = isNaN(width) ? 20 : width;
        height = isNaN(height) ? 20 : height;

        var shape = document.createElementNS(this.NameSpace_Svg, "rect");
        shape.setAttributeNS(null, "x", x);
        shape.setAttributeNS(null, "y", y);
        shape.setAttributeNS(null, "width", width);
        shape.setAttributeNS(null, "height", height);
        shape.setAttributeNS(null, "fill", fillColor);

        if (strokeColor != null)
            shape.setAttributeNS(null, "stroke", strokeColor);

        if (strokeDash != null)
            shape.setAttributeNS(null, "stroke-dasharray", strokeDash);

        if (opacity != null)
            shape.setAttributeNS(null, "opacity", opacity);

        shape.setAttributeNS(null, "id", id);


        return shape;
    };

    this.UpdateRectangle = function (rect, x, y, width, height)
    {
        rect.attr("x", x);
        rect.attr("y", y);
        rect.attr("width", width);
        rect.attr("height", height);
    };

    this.AddLine = function (x1, y1, x2, y2, style, id)
    {
        var shape = document.createElementNS(this.NameSpace_Svg, "line");
        shape.setAttributeNS(null, "x1", x1);
        shape.setAttributeNS(null, "y1", y1);
        shape.setAttributeNS(null, "x2", x2);
        shape.setAttributeNS(null, "y2", y2);
        shape.setAttributeNS(null, "style", style);
        shape.setAttributeNS(null, "id", id);

        return shape;
    };

    this.UpdateLine = function (line, x1, y1, x2, y2)
    {
        line.attr("x1", x1);
        line.attr("x2", x2);
        line.attr("y1", y1);
        line.attr("y2", y2);
    };

    this.AddPolygon = function ( pList ,  strokeColor, fillColor, strokeDash, strokeWidth, opacity, id, cssClass  )
    {
        document.GetTransform
        var shape = document.createElementNS(this.NameSpace_Svg, "polygon");
        var i; 

        

        shape.setAttributeNS(null, "points", pList );
        shape.setAttributeNS(null, "fill", fillColor); 
        
        if (strokeColor != null)
            shape.setAttributeNS(null, "stroke", strokeColor);
        
        if (strokeDash != null)
            shape.setAttributeNS(null, "stroke-dasharray", strokeDash);

        if(strokeWidth != null)
            shape.setAttributeNS(null, "stroke-width", strokeWidth);
        
        if (opacity != null)
            shape.setAttributeNS(null, "opacity", opacity);

        shape.setAttributeNS(null, "id", id);

        if (cssClass != null)
            shape.setAttribute("class", cssClass);


        return shape;
    };
    this.UpdatePolygon = function ( shape, pList )
    {
        var i;
        var pStr = '';
        for( i = 0 ; i < pList.length; i++ )  
        {
            pStr = pStr + ' ' + pList[i].x + "," + pList[i].y;
        }
        shape.attr("points", pStr);
    }



    this.AddEllipsisArc = function (radii, startPoint, endPoint, color, fill, strokeWidth, id) {
        //var radius = MathUtils.CartesianSegmentLength(centerPoint, endPoint);
        // TODO: support arcs > 180*
        var largeArcFlag = "0";//endAngle - startAngle <= 180 ? "0" : "1";
        var sweep = "1"; // todo: support argument
        var d = [
            "M", startPoint.X, startPoint.Y,
            "A", radii.X, radii.Y, 0, largeArcFlag, sweep, endPoint.X, endPoint.Y
        ].join(" ");

        color = color || "black";
        fill = fill || "none";
        strokeWidth = strokeWidth || "3";

        var shape = document.createElementNS(this.NameSpace_Svg, "path");
        shape.setAttributeNS(null, "d", d);
        shape.setAttributeNS(null, "fill", fill);
        shape.setAttributeNS(null, "stroke", color);
        shape.setAttributeNS(null, "stroke-width", strokeWidth);
        shape.setAttributeNS(null, "id", id);
        return shape;

    };

    this.AddCubicBezier = function (startPoint, relativeStartPointRef, endPoint, relativeEndPointRef, color, fill, strokeWidth, id) {

        // TODO: support arcs > 180*
        var largeArcFlag = "0";//endAngle - startAngle <= 180 ? "0" : "1";
        var sweep = "1"; // todo: support argument
        var controlPointX1 = startPoint.X + relativeStartPointRef.X;
        var controlPointY1 = startPoint.Y + relativeStartPointRef.Y;
        var controlPointX2 = endPoint.X + relativeEndPointRef.X;
        var controlPointY2 = endPoint.Y + relativeEndPointRef.Y;

        var d = [
            "M", startPoint.X, startPoint.Y,
            "C", controlPointX1, controlPointY1, controlPointX2, controlPointY2, endPoint.X, endPoint.Y
        ].join(" ");

        color = color || "black";
        fill = fill || "none";
        strokeWidth = strokeWidth || "3";

        var shape = document.createElementNS(this.NameSpace_Svg, "path");
        shape.setAttributeNS(null, "d", d);
        shape.setAttributeNS(null, "fill", fill);
        shape.setAttributeNS(null, "stroke", color);
        shape.setAttributeNS(null, "stroke-width", strokeWidth);
        shape.setAttributeNS(null, "id", id);
        return shape;

    };

    this.AddTriangle = function (PointList, style, id)
    {
        var pointsList = [];
        PointList.foreach(function (Point) {
            pointsList.Add(Point.ToString());
        });

        var points = pointsList.join(" ");

        var shape = document.createElementNS(this.NameSpace_Svg, "polygon");
        shape.setAttributeNS(null, "points", points);
        shape.setAttributeNS(null, "style", style);
        shape.setAttributeNS(null, "id", id);
        return shape;
    }


    this.CreateBlankSvg = function (width, height)
    {
        var svg = document.createElementNS(this.NameSpace_Svg, "svg");
        svg.setAttribute("width", width);
        svg.setAttribute("height", height);

        return svg;
    };

    this.CreateSvgGroup = function (id)
    {
        var g = document.createElementNS(this.NameSpace_Svg, "g");
        g.setAttributeNS(null, "id", id);

        return g;
    };

    this.CreateDefs = function (id)
    {
        var defs = document.createElementNS(this.NameSpace_Svg, "defs");
        defs.setAttributeNS(null, "id", id);

        return defs;
    };

    this.CreateClipPath = function (id)
    {
        var clipPath = document.createElementNS(this.NameSpace_Svg, "clipPath");
        clipPath.setAttributeNS(null, "id", id);

        return clipPath;
    };

    this.CreateUse = function (id, useId)
    {
        var useRect = document.createElementNS(this.NameSpace_Svg, "use");
        useRect.setAttributeNS(null, "id", id);

        useRect.setAttributeNS(this.NameSpace_Xlink, "xlink:href", "#" + useId);
        return useRect;
    };

    this.CreateTextableMetadata = function (textArea)
    {
        var textable = document.createElement("textable");
        textable.setAttribute("textarea", textArea);

        return textable;
    };

    this.CreateColorableMetadata = function (replaceColor, secretColor, region, specialTitle)
    {
        var colorable = document.createElement("Colorable");
        colorable.setAttribute("ReplaceColor", replaceColor);
        colorable.setAttribute("SecretColor", secretColor);
        colorable.setAttribute("region", region);
        colorable.setAttribute("SpecialTitle", specialTitle);

        return colorable;
    };

    this.CreateMetadata = function ()
    {
        return document.createElement("SBTData");
    };

    this.AddText = function (x, y, text, fill , style, id)
    {
        var shape = document.createElementNS(this.NameSpace_Svg, "text");
        shape.setAttributeNS(null, "x", x);
        shape.setAttributeNS(null, "y", y);
        shape.setAttributeNS(null, "fill", fill);
        shape.setAttributeNS(null, "style", style);
        shape.setAttributeNS(null, "class", "DisableSelection");

        shape.setAttributeNS(null, "unselectable", "on");

        var data = document.createTextNode(text);
        shape.appendChild(data);

        return shape;
    };

    //#region "ViewBox"
    this.GetSvgSize = function (id)
    {
        var svgSize = new Object();
        
        try
        {
            var coreImage = $("#" + id);

            svgSize.Width = coreImage.attr("width").ExtractNumber();
            svgSize.Height = coreImage.attr("height").ExtractNumber();

            var viewBox = coreImage[0].getAttribute("viewBox");
            if (viewBox == null)
                viewBox = coreImage[0].getAttribute("viewbox");
        }
        catch (e)
        {
            LogErrorMessage("SvgCreatorLibrary.GetSvgSize", e);
        }

        try
        {
            svgSize.ViewBox = new Object();

            if (viewBox != null)
            {
                viewBox = viewBox.replace(",", " ").replace("  ", " ");
                viewBox = viewBox.split(" ");

                svgSize.ViewBox.MinX = viewBox[0].ExtractNumber();
                svgSize.ViewBox.MinY = viewBox[1].ExtractNumber();
                svgSize.ViewBox.Width = viewBox[2].ExtractNumber();
                svgSize.ViewBox.Height = viewBox[3].ExtractNumber();
            }
        } catch (e)
        {
            LogErrorMessage("SvgCreatorLibrary.GetSvgSize2 viewbox: " + viewBox, e);
        }
           

            return svgSize;
       
        return svgSize;
    };

    this.UpdateSvgSize = function (id, svgSize)
    {
        if (svgSize.Width < 5 || svgSize.Height < 5 || svgSize.ViewBox.Height < 5 || svgSize.ViewBox.Width < 5)
            return;

        var coreImage = $("#" + id);

        coreImage.attr("width", svgSize.Width + "px");
        coreImage.attr("height", svgSize.Height + "px");

        if (svgSize.ViewBox != null)
        {
            coreImage[0].setAttributeNS(null, "viewBox", svgSize.ViewBox.MinX + " " + svgSize.ViewBox.MinY + " " + svgSize.ViewBox.Width + " " + svgSize.ViewBox.Height);
        }
    };

    this.UpdateSvgSize_WithDeltas = function (id, deltaWidth, deltaHeight)
    {
        try
        {
            var svgSize = this.GetSvgSize(id);
            

            if (deltaWidth != 0)
            {
                var width = svgSize.Width + deltaWidth;

                if (width > 5)
                    svgSize.Width = width
            }

            if (deltaHeight != 0)
            {
                var height = svgSize.Height + deltaHeight;

                if (height > 5)
                    svgSize.Height = height
            }


            if (svgSize.ViewBox != null)
            {
                if ((svgSize.ViewBox.Width + deltaWidth) > 5)
                    svgSize.ViewBox.Width = svgSize.ViewBox.Width + deltaWidth;

                if ((svgSize.ViewBox.Height + deltaHeight) > 5)
                    svgSize.ViewBox.Height = svgSize.ViewBox.Height + deltaHeight;
            }

            this.UpdateSvgSize(id, svgSize);
        }
        catch (e)
        {
            LogErrorMessage("SvgCreatorLibrary.UpdateSvgSize_WithDeltas", e);
        }

    }
    //#endregion 
    //#region "Transforms"

    this.WriteTransform = function (id, transform)
    {
        try
        {
            var svgElement = GetGlobalById(id);

            this._SetDefaultTransformValues(transform, "Translate", [0, 0]);
            this._SetDefaultTransformValues(transform, "Rotate", [0, 0, 0]);
            this._SetDefaultTransformValues(transform, "Scale", [1, 1]);

            var trans = "translate(" + transform.Translate[0] + ", " + transform.Translate[1] + ") ";
            trans += "rotate(" + transform.Rotate[0] + ", " + transform.Rotate[1] + ", " + transform.Rotate[2] + ") ";
            trans += "scale(" + transform.Scale[0] + ", " + transform.Scale[1] + ") ";

            svgElement.setAttributeNS(null, "transform", trans);
        }
        catch (e)
        {
            //DebugLine("SvgCreatorLibrary.WriteTransform: " + e);
        }
    };

    this._SetDefaultTransformValues = function (transform, param, defaults)
    {
        try
        {
            if (transform[param] == null)
                transform[param] = [];

            if (transform[param].length == 0)
                transform[param].push(defaults[0]);

            if (transform[param].length == 1)
                transform[param].push(defaults[1]);

            if (transform[param].length == 2 && defaults.length==3)
                transform[param].push(defaults[2]);

        }
        catch (e)
        {
            //DebugLine("SvgCreatorLibrary.GetTransform: " + e);

        }
    };

    this.GetTransform = function (id)
    {
        try
        {
            var svgElement = GetGlobalById(id);
            var transform = svgElement.getAttribute("transform");

            transform = (transform == null || transform == "") ? "translate(0,0) rotate(0,0,0), scale(1,1)" : transform;
 v
            var transformObject = new Object();
            transformObject.Translate = this._ExtractTransformValues(transform, "translate", [0,0]);
            transformObject.Rotate = this._ExtractTransformValues(transform, "rotate", [0,0,0]);
            transformObject.Scale = this._ExtractTransformValues(transform, "scale", [1,1]);

            if (isNaN(transformObject.Translate[0]))
            {
                //DebugLine("!");
            }
            return transformObject;
        } catch (e)
        {
            //DebugLine("SvgCreatorLibrary.GetTransform: " + e);
        }

    };

    this._ExtractTransformValues = function (transform, param, defaultValues)
    {
        try
        {
            var indexOfParam = transform.indexOf(param);
            if (indexOfParam < 0)
                return defaultValues;

            var values = transform.substring(indexOfParam + param.length, transform.indexOf(")", indexOfParam));
            values = SplitParenthesisNumeric(values);

            if (values.length<defaultValues.length)
            {
                for (var i = values.length; i < defaultValues.length; i++)
                    values.push(defaultValues[i]);
            }
            return values;

        }
        catch (e)
        {
            //DebugLine("SvgCreatorLibrary._ExtractTransformValues: " + e);
        }
        return defaultValues;
    }
    //#endregion
}

