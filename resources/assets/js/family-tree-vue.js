import { createApp } from 'vue'
import mitt from 'mitt'

const emitter = mitt()
const app = createApp({})
app.config.globalProperties.emitter = emitter
app.config.globalProperties.entityHeight = 60
app.config.globalProperties.entityWidth = 200
app.component('family-tree', require('./components/families/FamilyTree.vue').default)
app.component('FamilyNode', require('./components/families/FamilyNode.vue').default)
app.component('FamilyEntity', require('./components/families/FamilyEntity.vue').default)
app.component('FamilyRelations', require('./components/families/FamilyRelations.vue').default)
app.component('FamilyRelation', require('./components/families/FamilyRelation.vue').default)
app.component('FamilyChildren', require('./components/families/FamilyChildren.vue').default)
app.component('RelationLine', require('./components/families/RelationLine.vue').default)
app.component('ChildrenLine', require('./components/families/ChildrenLine.vue').default)
app.component('FamilyParentChildrenLine', require('./components/families/FamilyParentChildrenLine.vue').default)
app.mount('#family-tree');


window.ftTexts = {};

/**
 * Figure out the width of a child (when drawing a relation). This is used when calculating where to draw the next node
 * (the next relation)
 * @param child
 * @param index
 */
window.familyTreeChildWidth = function(child, index) {
    if (child.relations === undefined || child.relations.length === 0) {
        return 1;
    }
    // The minimum width based on the topmost elements. Since the first child starts below the first parent, we go
    // back 1
    let size = -1;

    /**
     * Loop on each of the child's relations, making this node wider (for each relation's size)
     * If it's just 3 relations, the node is 1+3 (relations) wide
     */
    child.relations.forEach(rel => {
        // Relation ads at least 1 width
        size++;
        if (rel.children !== undefined && rel.children.length > 0) {
            // If there are children, we start back at 0 because the node + rel already counts as two
            /*if (rel.children.length > 1) {
                min -= 2;
            } else if (rel.children.length > 0 && index === 0) {
                min -= 1;
            }*/
            /**
             * Loop each child of the relation, looking for the "widest" one
             * On each child, we need to get its total width (child + relation + children) and add it to the width
             * of the current child
             */
            rel.children.forEach((c, i) => {
                // Get each child's width, (child + relations + their children) and add it to the size.
                // Deduct one because each child starts on a new line and is pushed left
                let childWidth = window.familyTreeChildWidth(c, index);
                //console.log(c.entity_id, 'childWidth', childWidth);
                size += childWidth;
            });
        }
    });

    // The minimum width, in case a child has two relations but no children, if the amount of relations + itself
    let minWidth = child.relations.length + 1;
    // Get the largest calculated size
    return Math.max(minWidth, size);
};

/**
 * Count how wide a relation is, counting itself + all of its children
 */
window.familyTreeRelationWidth = function(relation, index) {
    // If a relation has no children, then it's simple
    if (relation.children === undefined || relation.children.length === 0) {
        return 1;
    }

    // Each relation takes up at least 1 width
    let size = 1;

    // Let's find out just how wide this relation is
    relation.children.forEach((child, i) => {
        // The first two children are below the parent and this entity, so they don't count as the minimum
        if (i > 1) {
            size++;
        }
        if (child.relations !== undefined && child.relations.length > 0) {
            // For each of the relation's children, calculate their width, and add that to the current size
            child.relations.forEach((c, i2) => {
                let tmp = window.familyTreeRelationWidth(c);
                console.log(c.entity_id, 'relWidth', tmp);
                size += tmp;
            });
        }
    });

    // Return the size of the tree, or the size of the children,
    // if none of the children have relations and their own children
    return Math.max(relation.children.length, size);
};

